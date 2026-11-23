<?php

namespace App\Http\Controllers;

use App\Services\JsonStorageService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(private JsonStorageService $storage) {}

    public function index(Request $request)
    {
        if ($request->query('logout')) {$request->session()->forget([
                'patient_logged_in',
                'patient_email',
                'patient_name',
                'privacy_agreed',
            ]);
            return redirect()->route('portal.index');
        }

        $allAppointments =$this->storage->read('appointments.json', []);
        $availableDoctors =$this->storage->read('doctors.json', []);
        $blockedSlots =$this->storage->read('blocked_slots.json', []);

        $isLoggedIn = (bool)$request->session()->get('patient_logged_in', false);
        $privacyAgreed = (bool)$request->session()->get('privacy_agreed', false);
        $declined = (bool)$request->query('declined', false);

        $patientName = (string)$request->session()->get('patient_name', '');
        $patientEmail = (string)$request->session()->get('patient_email', '');

        $myAppointments = [];
        if ($isLoggedIn &&$patientEmail !== '') {
            foreach ($allAppointments as$appt) {
                if (isset($appt['patient_email']) && strcasecmp((string) $appt['patient_email'],$patientEmail) === 0) {
                    $myAppointments[] =$appt;
                }
            }
        }

        return view('portal.index', [
            'isLoggedIn' => $isLoggedIn,
            'privacyAgreed' => $privacyAgreed,
            'declined' => $declined,
            'patientName' => $patientName,
            'patientEmail' => $patientEmail,
            'availableDoctors' => $availableDoctors,
            'blockedSlots' => $blockedSlots,
            'allAppointments' => $allAppointments,
            'myAppointments' => $myAppointments,
            'successRef' => $request->query('ref'),
            'toastMsg' => $request->query('msg'),
            'toastType' => $request->query('type', 'success'),
            'authError' => $request->session()->pull('auth_error'),
            'authSuccess' => $request->session()->pull('auth_success'),
        ]);
    }

    public function handlePost(Request $request)
    {
        $action = trim((string)$request->input('action', ''));

        if ($action === 'register') {
            $name = trim((string)$request->input('reg_name', ''));
            $email = trim((string)$request->input('reg_email', ''));
            $phone = trim((string)$request->input('reg_phone', ''));
            $password = trim((string)$request->input('reg_password', ''));

            if ($name === '' ||$email === '' || $password === '') {$request->session()->flash('auth_error', 'Full name, email, and password are required for registration.');
                return redirect()->route('portal.index');
            }

            $patients =$this->storage->read('patients.json', []);
            foreach ($patients as$patient) {
                if (isset($patient['email']) && strcasecmp((string)$patient['email'], $email) === 0) {$request->session()->flash('auth_error', 'Email is already registered! Please login.');
                    return redirect()->route('portal.index');
                }
            }

            $newPatient = [
                'name' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
                'email' => htmlspecialchars($email, ENT_QUOTES, 'UTF-8'), 'password' => password_hash($password, PASSWORD_DEFAULT),
            ];
            if ($phone !== '') {
                $newPatient['phone'] = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
            }

            $patients[] =$newPatient;
            $this->storage->write('patients.json',$patients);

            $request->session()->flash('auth_success', 'Registration successful! Please flip to login.');
            return redirect()->route('portal.index');
        }

        if ($action === 'login') {
            $email = trim((string)$request->input('login_email', ''));
            $password = trim((string)$request->input('login_password', ''));

            $patients = $this->storage->read('patients.json', []);$matchedPatient = null;

            foreach ($patients as $patient) {$storedHash = (string) ($patient['password'] ?? '');$validPassword = password_verify($password,$storedHash) || hash_equals($storedHash,$password);
                if (isset($patient['email']) && strcasecmp((string)$patient['email'], $email) === 0 &&$validPassword) {
                    $matchedPatient =$patient;
                    break;
                }
            }

            if ($matchedPatient) {
                $request->session()->put('patient_logged_in', true);$request->session()->put('patient_email', (string) $matchedPatient['email']);$request->session()->put('patient_name', (string) $matchedPatient['name']);$request->session()->put('privacy_agreed', false);

                return redirect()->route('portal.index');
            }

            $request->session()->flash('auth_error', 'Invalid email or password!');
            return redirect()->route('portal.index');
        }

        if ($action === 'submit_privacy') {
            $choice = (string)$request->input('privacy_choice', '');
            if ($choice === 'agree') {$request->session()->put('privacy_agreed', true);
                return redirect()->route('portal.index', [
                    'msg' => 'Welcome to Richwell Patient Portal!',
                    'type' => 'success',
                ]);
            }

            $request->session()->forget([
                'patient_logged_in',
                'patient_email',
                'patient_name',
                'privacy_agreed',
            ]);
            return redirect()->route('portal.index', ['declined' => 1]);
        }

        if ($action === 'cancel_appointment' &&$request->session()->get('patient_logged_in')) {
            $refNo = trim((string)$request->input('ref_no', ''));
            $sessionEmail = (string)$request->session()->get('patient_email', '');

            $appointments = $this->storage->read('appointments.json', []);$updated = false;

            foreach ($appointments as &$appt) {
                if (
                    ($appt['reference_no'] ?? '') ===$refNo &&
                    isset($appt['patient_email']) &&
                    strcasecmp((string) $appt['patient_email'],$sessionEmail) === 0
                ) {
                    $appt['status'] = 'Cancelled';$updated = true;
                    break;
                }
            }
            unset($appt);

            if ($updated) {
                $this->storage->write('appointments.json',$appointments);
                return redirect()->route('portal.index', [
                    'msg' => "Appointment {$refNo} cancelled successfully.",
                    'type' => 'success',
                ]);
            }
        }

        if ($action === 'reschedule_appointment' &&$request->session()->get('patient_logged_in')) {
            $refNo = trim((string)$request->input('ref_no', ''));
            $newDate = trim((string)$request->input('new_date', ''));
            $newSlot = trim((string)$request->input('new_slot', ''));
            $sessionEmail = (string)$request->session()->get('patient_email', '');

            $appointments =$this->storage->read('appointments.json', []);
            $blockedSlots =$this->storage->read('blocked_slots.json', []);

            $targetDoctor = '';
            foreach ($appointments as$appt) {
                if (($appt['reference_no'] ?? '') ===$refNo) {
                    $targetDoctor = (string) ($appt['doctor'] ?? '');
                    break;
                }
            }

            $conflict = false;
            foreach ($appointments as$appt) {
                if (
                    ($appt['reference_no'] ?? '') !==$refNo &&
                    ($appt['doctor'] ?? '') ===$targetDoctor &&
                    ($appt['appointment_date'] ?? '') ===$newDate &&
                    ($appt['time_slot'] ?? '') ===$newSlot &&
                    ($appt['status'] ?? 'Pending') !== 'Cancelled'
                ) {
                    $conflict = true;
                    break;
                }
            }

            if (!$conflict) {
                foreach ($blockedSlots as$blk) {
                    if (
                        ($blk['doctor'] ?? '') ===$targetDoctor &&
                        ($blk['date'] ?? '') ===$newDate &&
                        ($blk['time_slot'] ?? '') ===$newSlot
                    ) {
                        $conflict = true;
                        break;
                    }
                }
            }

            if ($conflict) {
                return redirect()->route('portal.index', [
                    'msg' => 'Selected slot is unavailable or already booked!',
                    'type' => 'error',
                ]);
            }

            foreach ($appointments as &$appt) {
                if (
                    ($appt['reference_no'] ?? '') ===$refNo &&
                    isset($appt['patient_email']) &&
                    strcasecmp((string) $appt['patient_email'], $sessionEmail) === 0 ) {$appt['appointment_date'] = htmlspecialchars($newDate, ENT_QUOTES, 'UTF-8');$appt['time_slot'] = htmlspecialchars($newSlot, ENT_QUOTES, 'UTF-8');$appt['status'] = 'Pending';
                    break;
                }
            }
            unset($appt);

            $this->storage->write('appointments.json',$appointments);

            return redirect()->route('portal.index', [
                'msg' => "Appointment {$refNo} rescheduled successfully.",
                'type' => 'success',
            ]);
        }

        return redirect()->route('portal.index');
    }

    public function processBooking(Request $request)
    {
        $patientName = trim((string)$request->input('patient_name', ''));
        $patientEmail = trim((string)$request->input('patient_email', ''));
        $department = trim((string)$request->input('department', ''));
        $doctor = trim((string)$request->input('doctor', ''));
        $appointmentDate = trim((string)$request->input('appointment_date', ''));
        $timeSlot = trim((string)$request->input('time_slot', ''));

        if (
            $patientName === '' ||$patientEmail === '' ||
            $department === '' ||$doctor === '' ||
            $appointmentDate === '' ||$timeSlot === ''
        ) {
            return redirect()->route('portal.index', [
                'msg' => 'All booking fields including time slot are required.',
                'type' => 'error',
            ]);
        }

        $appointments =$this->storage->read('appointments.json', []);
        $blockedSlots =$this->storage->read('blocked_slots.json', []);

        foreach ($appointments as$r) {
            if (
                ($r['doctor'] ?? '') ===$doctor &&
                ($r['appointment_date'] ?? '') ===$appointmentDate &&
                ($r['time_slot'] ?? '') ===$timeSlot &&
                ($r['status'] ?? 'Pending') !== 'Cancelled'
            ) {
                return redirect()->route('portal.index', [
                    'msg' => "Sorry, {$doctor} is already booked on {$appointmentDate} ({$timeSlot}).",
                    'type' => 'error',
                ]);
            }
        }

        foreach ($blockedSlots as$blk) {
            if (
                ($blk['doctor'] ?? '') ===$doctor &&
                ($blk['date'] ?? '') ===$appointmentDate &&
                ($blk['time_slot'] ?? '') ===$timeSlot
            ) {
                return redirect()->route('portal.index', [
                    'msg' => "Sorry, {$doctor} is unavailable on {$appointmentDate} ({$timeSlot}).",
                    'type' => 'error',
                ]);
            }
        }

        $refNo = 'RWH-' . strtoupper(substr(uniqid(), -6));
        $appointments[] = [
            'reference_no' => $refNo,
            'patient_name' => htmlspecialchars($patientName, ENT_QUOTES, 'UTF-8'), 'patient_email' => htmlspecialchars($patientEmail, ENT_QUOTES, 'UTF-8'),
            'department' => htmlspecialchars($department, ENT_QUOTES, 'UTF-8'), 'doctor' => htmlspecialchars($doctor, ENT_QUOTES, 'UTF-8'),
            'appointment_date' => htmlspecialchars($appointmentDate, ENT_QUOTES, 'UTF-8'), 'time_slot' => htmlspecialchars($timeSlot, ENT_QUOTES, 'UTF-8'),
            'status' => 'Pending',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->storage->write('appointments.json',$appointments);

        return redirect()->route('portal.index', ['ref' => $refNo]);
    }
}
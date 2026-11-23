<?php

namespace App\Http\Controllers;

use App\Services\JsonStorageService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(private JsonStorageService $storage) {}

    public function showLogin(Request $request)
    {
        return view('admin.login', [
            'error' => $request->session()->pull('admin_error'),
        ]);
    }

    public function login(Request $request)
    {
        $username = trim((string) $request->input('username', ''));
        $password = trim((string) $request->input('password', ''));

        $expectedUser = (string) env('RICHWELL_ADMIN_USER', 'admin');
        $expectedPass = (string) env('RICHWELL_ADMIN_PASS', 'richwell2026');

        if ($username === $expectedUser && $password === $expectedPass) {
            $request->session()->put('admin_logged_in', true);
            $request->session()->put('admin_user', 'admin');
            return redirect()->route('admin.dashboard');
        }

        $request->session()->flash('admin_error', 'Invalid admin username or password!');
        return redirect()->route('admin.login');
    }

    public function dashboard(Request $request)
    {
        if ($request->query('logout')) {
            $request->session()->forget(['admin_logged_in', 'admin_user']);
            $request->session()->regenerateToken();
            return redirect()->route('admin.login');
        }

        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $appointments = $this->safeJsonList($this->storage->read('appointments.json', []));
        $doctors = $this->safeJsonList($this->storage->read('doctors.json', []));
        $blockedSlots = $this->safeJsonList($this->storage->read('blocked_slots.json', []));
        $historyLogs = $this->safeJsonList($this->storage->read('admin_history.json', []));

        $search = strtolower(trim((string) $request->query('search', '')));
        $statusFilter = (string) $request->query('status_filter', 'All');

        $filteredAppointments = array_values(array_filter($appointments, function ($r) use ($search, $statusFilter) {
            $status = $r['status'] ?? 'Pending';
            $refNo = $r['reference_no'] ?? '';
            $patientName = $r['patient_name'] ?? '';
            $patientEmail = $r['patient_email'] ?? '';
            $doctor = $r['doctor'] ?? '';
            $department = $r['department'] ?? '';

            $matchesStatus = ($statusFilter === 'All') || ($status === $statusFilter);
            $matchesSearch = ($search === '') ||
                stripos((string) $refNo, $search) !== false ||
                stripos((string) $patientName, $search) !== false ||
                stripos((string) $patientEmail, $search) !== false ||
                stripos((string) $doctor, $search) !== false ||
                stripos((string) $department, $search) !== false;

            return $matchesStatus && $matchesSearch;
        }));

        $totalCount = count($appointments);
        $pendingCount = count(array_filter($appointments, fn($r) => ($r['status'] ?? '') === 'Pending'));
        $confirmedCount = count(array_filter($appointments, fn($r) => ($r['status'] ?? '') === 'Confirmed'));
        $completedCount = count(array_filter($appointments, fn($r) => ($r['status'] ?? '') === 'Completed'));

        return view('admin.dashboard', [
            'appointments' => $appointments,
            'filteredAppointments' => $filteredAppointments,
            'doctors' => $doctors,
            'blockedSlots' => $blockedSlots,
            'historyLogs' => $historyLogs,
            'search' => $request->query('search', ''),
            'statusFilter' => $statusFilter,
            'totalCount' => $totalCount,
            'pendingCount' => $pendingCount,
            'confirmedCount' => $confirmedCount,
            'completedCount' => $completedCount,
            'toastMsg' => $request->query('msg'),
            'toastType' => $request->query('type', 'success'),
        ]);
    }

    public function handleAction(Request $request)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $action = trim((string) $request->input('action', ''));
        $adminUser = (string) $request->session()->get('admin_user', 'admin');

        if ($action === 'update_status') {
            $ref = trim((string) $request->input('reference_no', ''));
            $status = trim((string) $request->input('status', ''));
            $allowedStatuses = ['Pending', 'Confirmed', 'Completed', 'Cancelled'];

            if ($ref !== '' && in_array($status, $allowedStatuses, true)) {
                $appointments = $this->safeJsonList($this->storage->read('appointments.json', []));
                foreach ($appointments as &$app) {
                    if (($app['reference_no'] ?? '') === $ref) {
                        $app['status'] = $status;
                        break;
                    }
                }
                unset($app);

                $this->storage->write('appointments.json', $appointments);
                $this->recordAdminHistory('Updated Status', "Appointment {$ref} set to {$status}", $adminUser);

                return redirect()->route('admin.dashboard', [
                    'msg' => "Status updated to {$status} for {$ref}.",
                    'type' => 'success',
                ]);
            }
        }

        if ($action === 'delete') {
            $ref = trim((string) $request->input('reference_no', ''));
            if ($ref !== '') {
                $appointments = $this->safeJsonList($this->storage->read('appointments.json', []));
                $filtered = array_values(array_filter($appointments, function ($app) use ($ref) {
                    return ($app['reference_no'] ?? '') !== $ref;
                }));

                $this->storage->write('appointments.json', $filtered);
                $this->recordAdminHistory('Deleted Appointment', "Removed appointment {$ref}", $adminUser);

                return redirect()->route('admin.dashboard', [
                    'msg' => "Appointment {$ref} deleted.",
                    'type' => 'success',
                ]);
            }
        }

        if ($action === 'add_doctor') {
            $docName = trim((string) $request->input('doc_name', ''));
            $docDept = trim((string) $request->input('doc_dept', ''));
            $docHours = trim((string) $request->input('doc_hours', ''));
            $docBio = trim((string) $request->input('doc_bio', ''));

            if ($docHours === '') {
                $docHours = 'Mon - Fri | 9:00 AM - 3:00 PM';
            }

            if ($docName !== '' && $docDept !== '') {
                $doctors = $this->safeJsonList($this->storage->read('doctors.json', []));
                $doctors[] = [
                    'name' => $docName,
                    'department' => $docDept,
                    'hours' => $docHours,
                    'bio' => $docBio,
                ];

                $this->storage->write('doctors.json', $doctors);
                $this->recordAdminHistory('Added Doctor', "{$docName} ({$docDept})", $adminUser);

                return redirect()->route('admin.dashboard', [
                    'msg' => "Doctor {$docName} added to directory.",
                    'type' => 'success',
                ]);
            }
        }

        if ($action === 'delete_doctor') {
            $docName = trim((string) $request->input('doc_name', ''));
            if ($docName !== '') {
                $doctors = $this->safeJsonList($this->storage->read('doctors.json', []));
                $filtered = array_values(array_filter($doctors, function ($d) use ($docName) {
                    return ($d['name'] ?? '') !== $docName;
                }));

                $this->storage->write('doctors.json', $filtered);
                $this->recordAdminHistory('Removed Doctor', "Removed {$docName} from directory", $adminUser);

                return redirect()->route('admin.dashboard', [
                    'msg' => "Doctor {$docName} removed.",
                    'type' => 'success',
                ]);
            }
        }

        if ($action === 'block_slot') {
            $blkDoc = trim((string) $request->input('blk_doctor', ''));
            $blkDate = trim((string) $request->input('blk_date', ''));
            $blkSlot = trim((string) $request->input('blk_slot', ''));

            if ($blkDoc !== '' && $blkDate !== '' && $blkSlot !== '') {
                $blocked = $this->safeJsonList($this->storage->read('blocked_slots.json', []));
                $alreadyBlocked = false;

                foreach ($blocked as $item) {
                    if (
                        ($item['doctor'] ?? '') === $blkDoc &&
                        ($item['date'] ?? '') === $blkDate &&
                        ($item['time_slot'] ?? '') === $blkSlot
                    ) {
                        $alreadyBlocked = true;
                        break;
                    }
                }

                if (!$alreadyBlocked) {
                    $blocked[] = [
                        'doctor' => $blkDoc,
                        'date' => $blkDate,
                        'time_slot' => $blkSlot,
                    ];
                    $this->storage->write('blocked_slots.json', $blocked);
                    $this->recordAdminHistory('Blocked Slot', "{$blkDoc} on {$blkDate} ({$blkSlot})", $adminUser);

                    return redirect()->route('admin.dashboard', [
                        'msg' => "Slot {$blkSlot} on {$blkDate} blocked for {$blkDoc}.",
                        'type' => 'success',
                    ]);
                }

                return redirect()->route('admin.dashboard', [
                    'msg' => 'That slot is already blocked.',
                    'type' => 'error',
                ]);
            }
        }

        if ($action === 'unblock_slot') {
            $index = filter_var($request->input('blk_index'), FILTER_VALIDATE_INT);
            $blocked = $this->safeJsonList($this->storage->read('blocked_slots.json', []));

            if ($index !== null && $index !== false && isset($blocked[$index])) {
                $removed = $blocked[$index];
                unset($blocked[$index]);
                $this->storage->write('blocked_slots.json', array_values($blocked));
                $this->recordAdminHistory(
                    'Unblocked Slot',
                    ($removed['doctor'] ?? 'Doctor') . ' on ' . ($removed['date'] ?? '') . ' (' . ($removed['time_slot'] ?? '') . ')',
                    $adminUser
                );

                return redirect()->route('admin.dashboard', [
                    'msg' => 'Slot unblocked.',
                    'type' => 'success',
                ]);
            }
        }

        return redirect()->route('admin.dashboard');
    }

    private function safeJsonList(mixed $rawData): array
    {
        return is_array($rawData) ? $rawData : [];
    }

    private function recordAdminHistory(string $actionLabel, string $details, string $adminUser = 'admin'): void
    {
        $logs = $this->safeJsonList($this->storage->read('admin_history.json', []));
        array_unshift($logs, [
            'timestamp' => date('Y-m-d H:i:s'),
            'admin' => $adminUser,
            'action' => $actionLabel,
            'details' => $details,
        ]);
        $this->storage->write('admin_history.json', array_slice($logs, 0, 100));
    }
}
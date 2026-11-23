<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Richwell Hospital Reservation System</title>
    <style>
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(10px) scale(0.99); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes slideInRight {
            0% { opacity: 0; transform: translateX(40px); }
            100% { opacity: 1; transform: translateX(0); }
        }
        @keyframes pulse-dot {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(15, 118, 110, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(15, 118, 110, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(15, 118, 110, 0); }
        }
        @keyframes runnerStride {
            0%, 100% { transform: translate(0, 0) scale(1); }
            45% { transform: translate(6px, -1px) scale(0.94); }
            85% { transform: translate(11px, 0) scale(0.75); opacity: 0.2; }
        }
        .animate-fade { animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        html, body { height: 100%; margin: 0; padding: 0; overflow-x: hidden; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.78), rgba(49, 46, 129, 0.68)), url('/assets/richwell%20hospital.png') no-repeat center center fixed;
            background-size: cover; padding: 20px; color: #1e293b; box-sizing: border-box;
            display: flex; justify-content: center; align-items: center;
        }
        .container {
            width: 100%; max-width: 980px; background: rgba(255, 255, 255, 0.97);
            padding: 30px; border-radius: 16px; box-shadow: 0 22px 48px rgba(15, 23, 42, 0.38);
            border-top: 6px solid #1e3a8a; backdrop-filter: blur(12px);
            min-height: 520px; max-height: 90vh; overflow-y: auto; border: 1px solid rgba(255, 255, 255, 0.85);
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            scrollbar-width: thin; scrollbar-color: #93c5fd #f1f5f9;
            display: flex; flex-direction: column;
        }
        .container::-webkit-scrollbar { width: 6px; }
        .container::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 8px; }
        .container::-webkit-scrollbar-thumb { background: #93c5fd; border-radius: 8px; }
        .main-content { flex: 1; display: flex; flex-direction: column; justify-content: center; }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }
        .header-title-group {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .logo-frame {
            width: 64px;
            height: 64px;
            border-radius: 14px;
            background: linear-gradient(135deg, #1e3a8a, #4338ca);
            border: 2px solid #c4b5fd;
            box-shadow: 0 6px 16px rgba(30, 58, 138, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6px;
            box-sizing: border-box;
            flex-shrink: 0;
        }
        .header img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: contrast(1.15) saturate(1.2) drop-shadow(0 2px 4px rgba(0, 0, 0, 0.35));
        }
        .header h1 { color: #1e3a8a; margin: 0; font-size: 19px; font-weight: 700; line-height: 1.2; }
        .header p { margin: 3px 0 0 0; color: #475569; font-size: 12px; line-height: 1.2; }
        .header-nav {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-shrink: 0;
            white-space: nowrap;
        }
        .user-badge {
            font-size: 13px;
            font-weight: 700;
            color: #1e3a8a;
            line-height: 1;
        }
        .admin-link {
            color: #1e3a8a;
            text-decoration: none;
            font-weight: 700;
            font-size: 13px;
            line-height: 1;
            transition: color 0.2s;
        }
        .admin-link:hover { color: #5b21b6; text-decoration: underline; }
        .logout-anim-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 11px;
            border-radius: 8px;
            background: #1e293b;
            color: #ffffff;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            line-height: 1;
            border: 1px solid #334155;
            box-shadow: 0 3px 10px rgba(15, 23, 42, 0.18);
            transition: transform 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
        }
        .logout-anim-btn:hover {
            background: #0f172a;
            transform: translateY(-1px);
            box-shadow: 0 5px 14px rgba(109, 40, 217, 0.24);
        }
        .logout-door-stage {
            position: relative;
            width: 24px;
            height: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;
        }
        .logout-doorway {
            width: 11px;
            height: 16px;
            border-radius: 2px;
            background: #4338ca;
            border: 1px solid #a5b4fc;
            position: relative;
            overflow: hidden;
            box-sizing: border-box;
        }
        .logout-door-leaf {
            position: absolute;
            inset: 0;
            background: #c4b5fd;
            transform-origin: left center;
            transform: scaleX(0.35);
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .logout-runner {
            position: absolute;
            left: 0;
            width: 11px;
            height: 14px;
            color: #ffffff;
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.25s ease;
        }
        .logout-anim-btn.logging-out .logout-runner {
            transform: translateX(11px) scale(0.75);
            opacity: 0;
            animation: runnerStride 0.35s ease-in forwards;
        }
        .logout-anim-btn.logging-out .logout-door-leaf {
            transform: scaleX(1);
            transition-delay: 0.22s;
        }
        .toast-container { position: fixed; top: 25px; right: 25px; z-index: 9999; }
        .toast {
            background: #0f766e; color: white; padding: 14px 22px; border-radius: 10px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25); font-size: 13px; font-weight: 600;
            display: flex; align-items: center; gap: 10px;
            animation: slideInRight 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .toast.error { background: #b91c1c; }
        .auth-wrapper {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 24px;
            align-items: center;
            width: 100%;
        }
        .auth-info-panel {
            background: linear-gradient(145deg, #f8fafc, #eef2ff);
            border: 1px solid #c7d2fe;
            border-left: 5px solid #1e3a8a;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 6px 20px rgba(30, 58, 138, 0.08);
        }
        .auth-info-panel h2 {
            margin: 0 0 8px 0;
            color: #1e3a8a;
            font-size: 20px;
        }
        .auth-info-panel p {
            margin: 0 0 16px 0;
            color: #475569;
            font-size: 13px;
            line-height: 1.55;
        }
        .auth-highlights {
            list-style: none;
            padding: 0;
            margin: 0 0 18px 0;
            display: grid;
            gap: 10px;
        }
        .auth-highlights li {
            background: #ffffff;
            border: 1px solid #dbeafe;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 12.5px;
            color: #1e293b;
            line-height: 1.4;
        }
        .auth-highlights strong { color: #1e3a8a; }
        .clinic-hours-box {
            background: #ffffff;
            border-radius: 10px;
            padding: 12px 14px;
            border: 1px dashed #0d9488;
            font-size: 12px;
            color: #334155;
            line-height: 1.5;
        }
        .flip-card-container { width: 100%; max-width: 420px; height: 455px; position: relative; perspective: 1000px; margin: 0 auto; }
        .flip-card-inner {
            position: absolute; width: 100%; height: 100%;
            transition: transform 0.6s cubic-bezier(0.4, 0.2, 0.2, 1);
            transform-style: preserve-3d; will-change: transform;
        }
        .flip-card-inner.flipped { transform: rotateY(180deg); }
        .flip-card-front, .flip-card-back {
            position: absolute; width: 100%; height: 100%;
            backface-visibility: hidden; -webkit-backface-visibility: hidden;
            background: #fff; padding: 26px 24px;
            border-radius: 14px;
            border: 1px solid #c4b5fd;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: stretch;
        }
        .flip-card-front {
            box-shadow: 0 18px 38px rgba(109, 40, 217, 0.42), 0 8px 20px rgba(76, 29, 149, 0.25), 0 0 0 1px rgba(139, 92, 246, 0.35);
        }
        .flip-card-back {
            transform: rotateY(180deg);
            box-shadow: 0 12px 28px rgba(30, 58, 138, 0.2), 0 4px 12px rgba(15, 118, 110, 0.1);
        }
        .auth-card-body {
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .auth-card-title {
            color: #1e3a8a;
            margin: 0 0 16px 0;
            text-align: center;
            font-size: 20px;
            font-weight: 700;
        }
        .auth-switch-link { text-align: center; margin-top: 16px; font-size: 13px; color: #1e3a8a; cursor: pointer; font-weight: bold; }
        .auth-switch-link:hover { color: #6d28d9; text-decoration: underline; }
        .password-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .password-wrap input {
            padding-right: 58px;
        }
        .toggle-pass-btn {
            position: absolute;
            right: 8px;
            background: #eef2ff;
            color: #1e3a8a;
            border: 1px solid #c7d2fe;
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            line-height: 1.2;
        }
        .toggle-pass-btn:hover { background: #e0e7ff; }
        .field-hint {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }
        .tabs {
            display: flex; gap: 8px; margin-bottom: 25px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;
            background: #f1f5f9; padding: 6px; border-radius: 12px; position: relative;
            box-sizing: border-box; overflow: hidden;
        }
        .tab-btn {
            background: transparent; border: none; padding: 10px 16px; font-weight: 600; border-radius: 8px;
            cursor: pointer; color: #475569; font-size: 13px; z-index: 2; flex: 1; text-align: center;
            transition: color 0.25s ease; box-sizing: border-box; white-space: nowrap; letter-spacing: -0.1px;
        }
        .tab-btn.active { color: white; font-weight: 600; }
        .tab-indicator {
            position: absolute; top: 6px; bottom: 6px; left: 6px;
            width: calc(33.333% - 4px); background: linear-gradient(135deg, #1e3a8a, #3730a3);
            border-radius: 8px; transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 1; box-shadow: 0 4px 15px rgba(30, 58, 138, 0.25);
            will-change: transform;
        }
        .section { display: none; opacity: 0; transform: translateY(10px); transition: opacity 0.3s ease, transform 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
        .section.active { display: block; opacity: 1; transform: translateY(0); }
        .alert { background: #ecfdf5; color: #065f46; padding: 12px; border-radius: 8px; margin-bottom: 15px; font-size: 13px; border: 1px solid #a7f3d0; }
        .error-alert { background: #fef2f2; color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 15px; font-size: 13px; border: 1px solid #fecaca; }
        form { display: grid; gap: 11px; }
        .form-group { display: flex; flex-direction: column; gap: 4px; }
        label { font-weight: 600; font-size: 13px; color: #1e3a8a; }
        input, select { padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; background: #fff; width: 100%; box-sizing: border-box; transition: all 0.2s; }
        input:focus, select:focus { border-color: #1e3a8a; outline: none; box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.14); }
        .btn-custom {
            background: linear-gradient(135deg, #1e3a8a, #3730a3); color: white; padding: 11px 20px; border: none; border-radius: 8px;
            cursor: pointer; font-size: 14.5px; font-weight: bold; width: 100%; transition: transform 0.2s ease, box-shadow 0.2s ease; margin-top: 6px;
            box-shadow: 0 4px 15px rgba(30, 58, 138, 0.25);
        }
        .btn-custom:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(30, 58, 138, 0.38); }
        .btn-custom:active { transform: scale(0.98); }
        .btn-sm { padding: 6px 12px; font-size: 11px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; color: white; text-decoration: none; display: inline-block; transition: transform 0.1s; }
        .btn-sm:active { transform: scale(0.95); }
        .btn-print { background: #0f766e; }
        .btn-resched { background: #d97706; }
        .btn-cancel { background: #b91c1c; }
        .slot-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; margin-top: 5px; }
        .slot-pill {
            background: #f1f5f9; border: 1.5px solid #cbd5e1; padding: 10px; border-radius: 8px; text-align: center;
            font-size: 12px; font-weight: 600; color: #1e3a8a; cursor: pointer; transition: all 0.2s ease;
        }
        .slot-pill:hover { background: #eef2ff; border-color: #1e3a8a; }
        .slot-pill.selected { background: #1e3a8a; color: white; border-color: #1e3a8a; box-shadow: 0 3px 10px rgba(30, 58, 138, 0.28); }
        .slot-pill.disabled { background: #f8fafc; color: #94a3b8; border-color: #e2e8f0; cursor: not-allowed; text-decoration: line-through; }
        .doctor-profile-card {
            background: #f8fafc; border-left: 4px solid #0f766e; padding: 12px 15px;
            border-radius: 8px; margin-top: 6px; font-size: 12px; color: #334155; border: 1px solid #e2e8f0;
        }
        .doctor-profile-card strong { color: #1e3a8a; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 13px; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04); }
        th, td { border: 1px solid #e2e8f0; padding: 12px; text-align: left; }
        th { background: #1e3a8a; color: white; font-weight: 600; }
        tr { transition: background-color 0.2s ease; }
        tr:hover { background-color: #f8fafc !important; }
        .status-badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 20px; font-weight: bold; font-size: 11px; }
        .pulse-dot { display: inline-block; width: 7px; height: 7px; border-radius: 50%; background: currentColor; animation: pulse-dot 1.5s infinite; margin-right: 6px; }
        .privacy-box { background: #f8fafc; border: 1px solid #c7d2fe; padding: 25px; border-radius: 12px; margin-bottom: 20px; }
        .radio-options { display: flex; flex-direction: column; gap: 12px; margin: 15px 0; }
        .radio-label { display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: normal !important; cursor: pointer; color: #1e293b; }
        .radio-label input[type="radio"] { width: 18px; height: 18px; cursor: pointer; accent-color: #1e3a8a; margin: 0; }
        .portal-footer {
            margin-top: 22px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            font-size: 11.5px;
            color: #64748b;
        }
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); z-index: 1000; justify-content: center; align-items: center; backdrop-filter: blur(4px); }
        .modal-box { background: #fff; padding: 25px; border-radius: 14px; width: 90%; max-width: 450px; box-shadow: 0 15px 35px rgba(15, 23, 42, 0.32); border-top: 5px solid #1e3a8a; animation: fadeIn 0.3s ease forwards; }
        .slip-box { border: 2px dashed #93c5fd; padding: 20px; background: #f8fafc; border-radius: 10px; margin: 15px 0; }
        .slip-row { display: flex; justify-content: space-between; padding: 7px 0; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
        @media (max-width: 820px) {
            body { padding: 12px; align-items: flex-start; }
            .container { padding: 18px; max-height: none; min-height: auto; border-radius: 14px; }
            .header { flex-direction: column; align-items: flex-start; gap: 12px; }
            .header-nav { width: 100%; flex-wrap: wrap; justify-content: flex-start; gap: 10px; white-space: normal; }
            .auth-wrapper { grid-template-columns: 1fr; gap: 18px; }
            .flip-card-container { max-width: 100%; height: 470px; }
            .tabs { flex-direction: column; gap: 6px; overflow: visible; }
            .tab-indicator { display: none; }
            .tab-btn { flex: none; width: 100%; text-align: left; }
            .tab-btn.active { background: linear-gradient(135deg, #1e3a8a, #3730a3); color: #ffffff; }
            #dashboard-section > div:first-child { flex-direction: column; align-items: stretch !important; gap: 10px; }
            #patientLiveSearch { max-width: 100% !important; width: 100%; }
            #booking-section form > div[style*="grid-template-columns: 1fr 1fr"] { grid-template-columns: 1fr !important; }
            table { display: block; overflow-x: auto; -webkit-overflow-scrolling: touch; white-space: nowrap; }
        }
        @media (max-width: 480px) {
            .logo-frame { width: 52px; height: 52px; }
            .header h1 { font-size: 16px; }
            .slot-grid { grid-template-columns: 1fr; }
        }
        
        @media print {
            body * { visibility: hidden; }
            #printModal, #printModal * { visibility: visible; }
            #printModal { position: absolute; left: 0; top: 0; width: 100%; background: white; border: none; }
            .no-print { display: none !important; }
        }
    </style>
    <script>
        const doctorsData = @json($availableDoctors);
        const blockedSlotsData = @json($blockedSlots);
        const allAppointmentsData = @json($allAppointments);
        function playLogoutAndGo(event, url) {
            event.preventDefault();
            const btn = event.currentTarget;
            if (btn.classList.contains('logging-out')) return;
            btn.classList.add('logging-out');
            setTimeout(() => {
                window.location.href = url;
            }, 650);
        }
        function flipCard(toRegister) {
            const cardInner = document.getElementById('flipCardInner');
            if (toRegister) cardInner.classList.add('flipped');
            else cardInner.classList.remove('flipped');
        }
        function togglePassword(inputId, btnElement) {
            const input = document.getElementById(inputId);
            if (!input) return;
            if (input.type === 'password') {
                input.type = 'text';
                btnElement.textContent = 'Hide';
            } else {
                input.type = 'password';
                btnElement.textContent = 'Show';
            }
        }
        function switchTab(tabId, element, index) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            element.classList.add('active');
            const indicator = document.getElementById('tabIndicator');
            if (indicator) {
                indicator.style.transform = `translateX(${index * 100}%)`;
            }
            document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active'));
            setTimeout(() => {
                document.getElementById(tabId).classList.add('active');
            }, 40);
        }
        function updateDoctorCardAndSlots() {
            const select = document.getElementById('doctorSelect');
            const card = document.getElementById('doctorProfileCard');
            if (!select || !card) return;
            const selectedName = select.value;
            const doc = doctorsData.find(d => d.name === selectedName);
            if (doc) {
                card.innerHTML = `
                    <strong>🩺 ${doc.name}</strong> — <span>${doc.department}</span><br>
                    <span style="color:#0f766e; font-weight:600;">🕒 Hours: ${doc.hours || 'Mon - Fri | 9:00 AM - 3:00 PM'}</span><br>
                    <span style="color:#475569; display:block; margin-top:4px;">${doc.bio || 'Experienced Richwell Hospital outpatient specialist.'}</span>
                `;
            }
            refreshSlotPills();
        }
        function selectSlot(element, slotValue) {
            if (element.classList.contains('disabled')) return;
            document.querySelectorAll('.slot-pill').forEach(p => p.classList.remove('selected'));
            element.classList.add('selected');
            document.getElementById('hiddenTimeSlot').value = slotValue;
        }
        function refreshSlotPills() {
            const docSelect = document.getElementById('doctorSelect');
            const dateInput = document.getElementById('appointmentDateInput');
            if (!docSelect || !dateInput) return;
            const docName = docSelect.value;
            const dateVal = dateInput.value;
            const pills = document.querySelectorAll('.slot-pill');
            pills.forEach(pill => {
                const slotVal = pill.getAttribute('data-slot');
                pill.classList.remove('disabled', 'selected');
                let isTaken = false;
                if (blockedSlotsData.some(b => b.doctor === docName && b.date === dateVal && b.time_slot === slotVal)) {
                    isTaken = true;
                }
                if (allAppointmentsData.some(a => a.doctor === docName && a.appointment_date === dateVal && a.time_slot === slotVal && a.status !== 'Cancelled')) {
                    isTaken = true;
                }
                if (isTaken) {
                    pill.classList.add('disabled');
                }
            });
            document.getElementById('hiddenTimeSlot').value = '';
        }
        function liveSearchTable(inputId, tableId) {
            const query = document.getElementById(inputId).value.toLowerCase();
            const rows = document.querySelectorAll(`#${tableId} tbody tr`);
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        }
        function openReschedModal(refNo, doctorName, currentDate, currentSlot) {
            document.getElementById('reschedRef').value = refNo;
            document.getElementById('reschedTitle').innerText = `Reschedule (${refNo}) - ${doctorName}`;
            document.getElementById('reschedDate').value = currentDate;
            document.getElementById('reschedModal').style.display = 'flex';
        }
        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }
        function openPrintSlip(ref, name, dept, doc, date, slot, status) {
            document.getElementById('slipRef').innerText = ref;
            document.getElementById('slipName').innerText = name;
            document.getElementById('slipDept').innerText = dept;
            document.getElementById('slipDoc').innerText = doc;
            document.getElementById('slipDate').innerText = date;
            document.getElementById('slipSlot').innerText = slot;
            document.getElementById('slipStatus').innerText = status;
            document.getElementById('printModal').style.display = 'flex';
        }
        window.addEventListener('DOMContentLoaded', () => {
            updateDoctorCardAndSlots();
            const toast = document.getElementById('toastAlert');
            if (toast) {
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => toast.remove(), 500);
                }, 4000);
            }
        });
    </script>
</head>
<body>
@if ($toastMsg || $successRef)
    <div class="toast-container" id="toastAlert">
        <div class="toast {{ $toastType === 'error' ? 'error' : '' }}">
            <span>{{ $toastType === 'error' ? '⚠️' : '✅' }}</span>
            <span>
                @if ($successRef)
                    Appointment Booked! Ref: {{ $successRef }}
                @else
                    {{ $toastMsg }}
                @endif
            </span>
        </div>
    </div>
@endif
<div class="container animate-fade">
    <div class="header">
        <div class="header-left">
            <div class="logo-frame">
                <img src="/assets/logo.png" alt="Richwell Logo">
            </div>
            <div class="header-title-group">
                <h1>Richwell Hospital Reservation System</h1>
                <p>Secure Outpatient Appointment Booking & Live Tracking</p>
            </div>
        </div>
        <div class="header-nav">
            @if ($isLoggedIn)
                <span class="user-badge">👤 {{ $patientName }}</span>
                <a href="{{ route('portal.index', ['logout' => 1]) }}" class="logout-anim-btn" onclick="playLogoutAndGo(event, this.href)">
                    <span>Log Out</span>
                    <span class="logout-door-stage" aria-hidden="true">
                        <svg class="logout-runner" viewBox="0 0 16 16" fill="currentColor">
                            <circle cx="9" cy="2.8" r="1.6" />
                            <path d="M6.8 5.2l2.6 1.2 1.8-.9.7 1.3-2.2 1.1-.8 2.4 2.1 1.9-1 1.1-2.4-2.2-.8 2.4-1.5-.4 1-3-1.4-.7-1.1 1.7-1.2-.8 1.6-2.4z" />
                        </svg>
                        <span class="logout-doorway"><span class="logout-door-leaf"></span></span>
                    </span>
                </a>
            @endif
            <a href="{{ route('admin.login') }}" class="admin-link">Admin Dashboard &rarr;</a>
        </div>
    </div>
    <div class="main-content">
        @if ($declined)
            <div class="error-alert animate-fade">
                You chose not to agree to the privacy policy. Your session has been ended. You may log back in anytime if you wish to accept terms and continue.
            </div>
        @endif
        @if (!$isLoggedIn)
            <div class="auth-wrapper animate-fade">
                <div class="auth-info-panel">
                    <h2>Outpatient Care Made Simple</h2>
                    <p>
                        Welcome to the <strong>Richwell Hospital</strong> patient reservation portal. Sign in or create an account to schedule outpatient consultations, check live queue status, and manage your visits.
                    </p>
                    <ul class="auth-highlights">
                        <li><strong>🗓️ Real-Time Slot Selection:</strong> Pick available time slots up to 5:00 PM and avoid double-booked schedules.</li>
                        <li><strong>🖨️ Instant Confirmation Slip:</strong> View and print your appointment reference slip for triage check-in.</li>
                        <li><strong>🔒 Privacy-First Portal:</strong> Review and confirm our data privacy commitment before accessing your records.</li>
                    </ul>
                    <div class="clinic-hours-box">
                        <strong>🕒 Outpatient Service Hours:</strong> 8:00 AM – 5:00 PM (varies by specialist)<br>
                        <strong>📌 Triage Reminder:</strong> Please arrive at least 15 minutes before your reserved slot.
                    </div>
                </div>
                <div class="flip-card-container">
                    <div id="flipCardInner" class="flip-card-inner">
                        <div class="flip-card-front">
                            <div class="auth-card-body">
                                <h3 class="auth-card-title">Patient Login</h3>
                                @if ($authError)
                                    <div class="error-alert" style="margin-bottom: 10px; padding: 7px 10px; font-size: 12px; text-align: center;">{{ $authError }}</div>
                                @endif
                                @if ($authSuccess)
                                    <div class="alert" style="margin-bottom: 10px; padding: 7px 10px; font-size: 12px; text-align: center;">{{ $authSuccess }}</div>
                                @endif
                                <form action="{{ route('portal.post') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="login">
                                    <div class="form-group">
                                        <label>Email Address:</label>
                                        <input type="email" name="login_email" placeholder="you@example.com" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Password:</label>
                                        <div class="password-wrap">
                                            <input type="password" name="login_password" id="loginPasswordInput" placeholder="Enter your password" required>
                                            <button type="button" class="toggle-pass-btn" onclick="togglePassword('loginPasswordInput', this)">Show</button>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn-custom">Login to Portal</button>
                                </form>
                                <div class="auth-switch-link" onclick="flipCard(true)">
                                    Don't have an account? <u>Sign Up</u>
                                </div>
                            </div>
                        </div>
                        <div class="flip-card-back">
                            <div class="auth-card-body">
                                <h3 class="auth-card-title" style="margin-bottom: 12px;">Create an Account</h3>
                                <form action="{{ route('portal.post') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="register">
                                    <div class="form-group">
                                        <label>Full Name:</label>
                                        <input type="text" name="reg_name" placeholder="e.g. Juan Dela Cruz" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Email Address:</label>
                                        <input type="email" name="reg_email" placeholder="you@example.com" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Mobile Number <span style="font-weight:400; color:#64748b;">(Optional)</span>:</label>
                                        <input type="tel" name="reg_phone" placeholder="09XX-XXX-XXXX">
                                    </div>
                                    <div class="form-group">
                                        <label>Password:</label>
                                        <div class="password-wrap">
                                            <input type="password" name="reg_password" id="regPasswordInput" minlength="6" placeholder="Create a password" required>
                                            <button type="button" class="toggle-pass-btn" onclick="togglePassword('regPasswordInput', this)">Show</button>
                                        </div>
                                        <span class="field-hint">Use at least 6 characters for your account.</span>
                                    </div>
                                    <button type="submit" class="btn-custom" style="background: linear-gradient(135deg, #0f766e, #115e59);">Register Account</button>
                                </form>
                                <div class="auth-switch-link" onclick="flipCard(false)">
                                    Already have an account? <u>Login</u>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif (!$privacyAgreed)
            <div class="privacy-box animate-fade">
                <h3 style="color: #1e3a8a; margin-top: 0;">🔒 Data Privacy Policy Agreement</h3>
                <p style="font-size: 13px; color: #475569; line-height: 1.5;">
                    Welcome, <strong>{{ $patientName }}</strong>! Before accessing your dashboard, please review our data privacy commitment:
                </p>
                <ul style="font-size: 13px; color: #334155; padding-left: 20px; line-height: 1.6;">
                    <li>Your personal information and appointment records are encrypted and stored securely.</li>
                    <li>Data is used strictly for scheduling outpatient appointments within Richwell Hospital.</li>
                    <li>We guarantee that your records will <strong>never</strong> be leaked or shared with third parties.</li>
                </ul>
                <form action="{{ route('portal.post') }}" method="POST" style="margin-top: 20px;">
                    @csrf
                    <input type="hidden" name="action" value="submit_privacy">
                    <label style="color: #1e3a8a; font-weight: bold; font-size: 14px; display: block; margin-bottom: 5px;">Do you agree to our Privacy Policy terms?</label>
                    <div class="radio-options">
                        <label class="radio-label">
                            <input type="radio" name="privacy_choice" value="agree" required>
                            <span>Yes, I agree and wish to continue</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="privacy_choice" value="disagree" required>
                            <span>No, I am not agreeing and wish to exit</span>
                        </label>
                    </div>
                    <button type="submit" class="btn-custom" style="max-width: 250px; margin-top: 15px;">Proceed</button>
                </form>
            </div>
        @else
            <div class="tabs">
                <div id="tabIndicator" class="tab-indicator"></div>
                <button type="button" class="tab-btn active" onclick="switchTab('dashboard-section', this, 0)">My Dashboard & Tracking</button>
                <button type="button" class="tab-btn" onclick="switchTab('booking-section', this, 1)">Book Appointment</button>
                <button type="button" class="tab-btn" onclick="switchTab('doctors-directory', this, 2)">Doctors Directory</button>
            </div>
            <div id="dashboard-section" class="section active">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <h3 style="color: #1e3a8a; margin: 0;">Welcome, {{ $patientName }}!</h3>
                    <input type="text" id="patientLiveSearch" placeholder="🔍 Instant search appointments..." onkeyup="liveSearchTable('patientLiveSearch', 'patientAppTable')" style="max-width: 250px; padding: 7px 10px; font-size: 12px;">
                </div>
                <table id="patientAppTable">
                    <thead>
                        <tr>
                            <th>Reference No</th>
                            <th>Department</th>
                            <th>Doctor</th>
                            <th>Schedule</th>
                            <th>Status</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (empty($myAppointments))
                            <tr><td colspan="6" style="text-align: center; color: #64748b; padding: 20px;">You have no active reservations yet. Click "Book Appointment" above.</td></tr>
                        @else
                            @foreach ($myAppointments as $r)
                                @php
                                    $status = $r['status'] ?? 'Pending';
                                    $badgeBg = '#fef3c7; color: #b45309;';
                                    if ($status === 'Confirmed') $badgeBg = '#dcfce7; color: #15803d;';
                                    elseif ($status === 'Completed') $badgeBg = '#dbeafe; color: #1d4ed8;';
                                    elseif ($status === 'Cancelled') $badgeBg = '#fee2e2; color: #b91c1c;';
                                @endphp
                                <tr>
                                    <td><strong>{{ $r['reference_no'] ?? '' }}</strong></td>
                                    <td>{{ $r['department'] ?? '' }}</td>
                                    <td>{{ $r['doctor'] ?? '' }}</td>
                                    <td>{{ ($r['appointment_date'] ?? '') . ' | ' . ($r['time_slot'] ?? '') }}</td>
                                    <td>
                                        <span class="status-badge" style="background: {{ $badgeBg }}">
                                            <span class="pulse-dot"></span>{{ $status }}
                                        </span>
                                    </td>
                                    <td style="text-align: center; white-space: nowrap;">
                                        @if ($status === 'Confirmed' || $status === 'Pending')
                                            <button type="button" class="btn-sm btn-print" onclick="openPrintSlip(
                                                '{{ addslashes($r['reference_no'] ?? '') }}',
                                                '{{ addslashes($patientName) }}',
                                                '{{ addslashes($r['department'] ?? '') }}',
                                                '{{ addslashes($r['doctor'] ?? '') }}',
                                                '{{ addslashes($r['appointment_date'] ?? '') }}',
                                                '{{ addslashes($r['time_slot'] ?? '') }}',
                                                '{{ addslashes($status) }}'
                                            )">🖨️ Slip</button>
                                            <button type="button" class="btn-sm btn-resched" onclick="openReschedModal(
                                                '{{ addslashes($r['reference_no'] ?? '') }}',
                                                '{{ addslashes($r['doctor'] ?? '') }}',
                                                '{{ addslashes($r['appointment_date'] ?? '') }}',
                                                '{{ addslashes($r['time_slot'] ?? '') }}'
                                            )">📅 Reschedule</button>
                                            <form action="{{ route('portal.post') }}" method="POST" style="display:inline;" onsubmit="return confirm('Cancel this appointment and release the slot?');">
                                                @csrf
                                                <input type="hidden" name="action" value="cancel_appointment">
                                                <input type="hidden" name="ref_no" value="{{ $r['reference_no'] ?? '' }}">
                                                <button type="submit" class="btn-sm btn-cancel">✖ Cancel</button>
                                            </form>
                                        @else
                                            <span style="color:#94a3b8; font-size:11px;">No actions</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div id="booking-section" class="section">
                <h3 style="color: #1e3a8a; margin-top: 0;">Book an Outpatient Appointment</h3>
                <form action="{{ route('portal.process') }}" method="POST">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="form-group">
                            <label>Patient Full Name:</label>
                            <input type="text" name="patient_name" value="{{ $patientName }}" readonly style="background: #f8fafc;" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address:</label>
                            <input type="email" name="patient_email" value="{{ $patientEmail }}" readonly style="background: #f8fafc;" required>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="form-group">
                            <label>Department:</label>
                            <select name="department" required>
                                @php
                                    $uniqueDepts = [];
                                    foreach ($availableDoctors as $d) {
                                        if (!empty($d['department'])) {
                                            $uniqueDepts[$d['department']] = true;
                                        }
                                    }
                                @endphp
                                @if (empty($uniqueDepts))
                                    <option value="">No departments available</option>
                                @else
                                    @foreach (array_keys($uniqueDepts) as $deptName)
                                        <option value="{{ $deptName }}">{{ $deptName }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Attending Doctor:</label>
                            <select name="doctor" id="doctorSelect" onchange="updateDoctorCardAndSlots()" required>
                                @if (empty($availableDoctors))
                                    <option value="">No doctors available</option>
                                @else
                                    @foreach ($availableDoctors as $doc)
                                        <option value="{{ $doc['name'] ?? '' }}">{{ $doc['name'] ?? '' }} ({{ $doc['department'] ?? '' }})</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div id="doctorProfileCard" class="doctor-profile-card"></div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; align-items: start;">
                        <div class="form-group">
                            <label>Appointment Date:</label>
                            <input type="date" name="appointment_date" id="appointmentDateInput" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" onchange="refreshSlotPills()" required>
                        </div>
                        <div class="form-group">
                            <label>Select Time Slot:</label>
                            <input type="hidden" name="time_slot" id="hiddenTimeSlot" required>
                            <div class="slot-grid">
                                <div class="slot-pill" data-slot="08:00 AM - 09:00 AM" onclick="selectSlot(this, '08:00 AM - 09:00 AM')">08:00 AM - 09:00 AM</div>
                                <div class="slot-pill" data-slot="09:00 AM - 10:00 AM" onclick="selectSlot(this, '09:00 AM - 10:00 AM')">09:00 AM - 10:00 AM</div>
                                <div class="slot-pill" data-slot="10:00 AM - 11:00 AM" onclick="selectSlot(this, '10:00 AM - 11:00 AM')">10:00 AM - 11:00 AM</div>
                                <div class="slot-pill" data-slot="01:00 PM - 02:00 PM" onclick="selectSlot(this, '01:00 PM - 02:00 PM')">01:00 PM - 02:00 PM</div>
                                <div class="slot-pill" data-slot="02:00 PM - 03:00 PM" onclick="selectSlot(this, '02:00 PM - 03:00 PM')">02:00 PM - 03:00 PM</div>
                                <div class="slot-pill" data-slot="03:00 PM - 04:00 PM" onclick="selectSlot(this, '03:00 PM - 04:00 PM')">03:00 PM - 04:00 PM</div>
                                <div class="slot-pill" data-slot="04:00 PM - 05:00 PM" onclick="selectSlot(this, '04:00 PM - 05:00 PM')">04:00 PM - 05:00 PM</div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn-custom" style="margin-top: 15px;">Confirm Reservation</button>
                </form>
            </div>
            <div id="doctors-directory" class="section">
                <h3 style="color: #1e3a8a; margin-top: 0;">Medical Specialists & Consultation Hours</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 15px; margin-top: 15px;">
                    @foreach ($availableDoctors as $doc)
                        <div style="background: #fff; border: 1px solid #e2e8f0; border-top: 4px solid #1e3a8a; border-radius: 10px; padding: 18px; box-shadow: 0 4px 15px rgba(15, 23, 42, 0.04);">
                            <h4 style="margin: 0 0 5px 0; color: #1e3a8a;">🩺 {{ $doc['name'] ?? '' }}</h4>
                            <span style="background: #eef2ff; color: #4338ca; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: bold;">
                                {{ $doc['department'] ?? '' }}
                            </span>
                            <p style="font-size: 12px; color: #475569; margin: 10px 0; line-height: 1.4;">
                                {{ $doc['bio'] ?? 'Outpatient consultation and medical diagnosis.' }}
                            </p>
                            <div style="font-size: 11px; color: #0f766e; font-weight: bold;">
                                🕒 {{ $doc['hours'] ?? 'Mon - Fri | 9:00 AM - 3:00 PM' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    <div class="portal-footer">
        <span><strong>Richwell Hospital Outpatient Portal</strong> &bull; Appointments depend on doctor hours and real-time slot availability.</span>
        <span>For urgent emergencies, proceed directly to Emergency Triage.</span>
    </div>
</div>
<div id="reschedModal" class="modal-overlay">
    <div class="modal-box">
        <h3 id="reschedTitle" style="color: #1e3a8a; margin-top: 0;">Reschedule Appointment</h3>
        <form action="{{ route('portal.post') }}" method="POST">
            @csrf
            <input type="hidden" name="action" value="reschedule_appointment">
            <input type="hidden" name="ref_no" id="reschedRef">
            <div class="form-group">
                <label>New Date:</label>
                <input type="date" name="new_date" id="reschedDate" min="{{ date('Y-m-d') }}" required>
            </div>
            <div class="form-group">
                <label>New Time Slot:</label>
                <select name="new_slot" required>
                    <option value="08:00 AM - 09:00 AM">08:00 AM - 09:00 AM</option>
                    <option value="09:00 AM - 10:00 AM">09:00 AM - 10:00 AM</option>
                    <option value="10:00 AM - 11:00 AM">10:00 AM - 11:00 AM</option>
                    <option value="01:00 PM - 02:00 PM">01:00 PM - 02:00 PM</option>
                    <option value="02:00 PM - 03:00 PM">02:00 PM - 03:00 PM</option>
                    <option value="03:00 PM - 04:00 PM">03:00 PM - 04:00 PM</option>
                    <option value="04:00 PM - 05:00 PM">04:00 PM - 05:00 PM</option>
                </select>
            </div>
            <div style="display: flex; gap: 10px; margin-top: 15px;">
                <button type="submit" class="btn-custom" style="margin:0;">Save Changes</button>
                <button type="button" class="btn-custom" style="background:#64748b; margin:0;" onclick="closeModal('reschedModal')">Close</button>
            </div>
        </form>
    </div>
</div>
<div id="printModal" class="modal-overlay">
    <div class="modal-box" style="max-width: 480px;">
        <div style="text-align: center;">
            <h3 style="color: #1e3a8a; margin: 0;">🏥 RICHWELL HOSPITAL</h3>
            <p style="font-size: 12px; color: #64748b; margin: 3px 0;">Outpatient Appointment Confirmation Slip</p>
        </div>
        <div class="slip-box">
            <div class="slip-row"><span>Reference No:</span> <strong id="slipRef" style="color:#1e3a8a;"></strong></div>
            <div class="slip-row"><span>Patient Name:</span> <strong id="slipName"></strong></div>
            <div class="slip-row"><span>Department:</span> <span id="slipDept"></span></div>
            <div class="slip-row"><span>Attending Doctor:</span> <strong id="slipDoc"></strong></div>
            <div class="slip-row"><span>Date:</span> <span id="slipDate"></span></div>
            <div class="slip-row"><span>Time Slot:</span> <span id="slipSlot"></span></div>
            <div class="slip-row" style="border:none;"><span>Status:</span> <strong id="slipStatus" style="color:#0f766e;"></strong></div>
        </div>
        <p style="font-size: 11px; color: #475569; text-align: center;">
            Please arrive 15 minutes before your schedule and present this slip at the Outpatient Triage Desk.
        </p>
        <div class="no-print" style="display: flex; gap: 10px; margin-top: 15px;">
            <button type="button" class="btn-custom" style="margin:0;" onclick="window.print()">🖨️ Print / Save PDF</button>
            <button type="button" class="btn-custom" style="background:#64748b; margin:0;" onclick="closeModal('printModal')">Close</button>
        </div>
    </div>
</div>
</body>
</html>
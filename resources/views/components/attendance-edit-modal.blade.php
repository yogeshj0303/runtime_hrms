<!-- Edit Attendance Modal -->
<div class="modal fade" id="editAttendanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title">Edit Attendance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('attendance.update-punches') }}" method="POST" id="editAttendanceForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="attendance_id" id="editAttendanceId">
                    <input type="hidden" name="employee_id" id="editEmployeeId">
                    <input type="hidden" name="attendance_date" id="editAttendanceDate">
                    
                    <div class="mb-3">
                        <label for="editStatus" class="form-label">Attendance Status</label>
                        <select name="status" id="editStatus" class="form-select" required>
                            <option value="Present">Present</option>
                            <option value="Half Day">Half Day</option>
                            <option value="Absent">Absent</option>
                            <option value="Leave">Leave</option>
                        </select>
                    </div>

                    <div class="row g-3">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="editStart" class="form-label">Start Time</label>
                                <input type="time" class="form-control" name="punch_in_time" id="editStart">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="editEnd" class="form-label">End Time</label>
                                <input type="time" class="form-control" name="punch_out_time" id="editEnd">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

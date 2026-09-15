# Daily Attendance - Testing & Comparison Report

## 39. Daily Attendance Timeline UI & Filters
- **Expected (Runtime):** The page must display a visual timeline for each employee, include a **Month Selector** filter, and show the employee's **Designation** and **Punch Method** (e.g., QR Code, Biometric) alongside the timeline.
- **Current (SomyaHRMS):** The timeline UI exists in `daily.blade.php` and the filter dropdowns are present, but:
  1. No **Month Selector** – only a single date picker is provided.
  2. The employee card header shows only the name and code; the **Designation** is missing.
  3. The **Punch Method** icon/badge is not rendered.
- **Missing/Issue:** Add a month selector input, fetch and display the employee's designation, and retrieve the `punch_method` from `AttendanceDailyDetail` to show the appropriate icon.

## 40. Action Buttons (View & Edit)
- **Expected (Runtime):** Each employee card should have functional **View** and **Edit** buttons that open modals to display punch details or edit attendance.
- **Current (SomyaHRMS):** The buttons are present but are dead links (`href="#"`).
- **Missing/Issue:** Wire the buttons to the same modals built for the Daily Punches module (View Punch Details & Edit Attendance), using `data-bs-toggle="modal"` and appropriate AJAX calls.

## 41. Monthly Attendance Calendar UI & Filters
- **Expected (Runtime):** A calendar view for the selected month showing each employee’s daily attendance status with symbols (P, A, W, H, EL, CO). The page must include a **Month Selector** filter, dropdowns for Business Unit, Location, Cost Center, Department, and a **Search Employee Name** field. The left‑hand side should display an **Employee Information Panel** (Name, Code, DOJ, DOR, Location, Department, Designation, Default Shift). Clicking a date opens an **Update Attendance** modal.
- **Current (SomyaHRMS):** The module does not exist – there is no calendar view, no month selector, and no employee information panel. Only the Daily Attendance page is present.
- **Missing/Issue:** Create a new view `resources/views/admin/employee/attendance/monthly.blade.php` with the described UI components, and a controller method `AttendanceMonthlyController@index` that supplies the necessary data (employees, shifts, attendance records) for the selected month.

## 42. Attendance Update (Edit Attendance) Modal
- **Expected (Runtime):** Clicking a date opens a modal allowing HR to edit attendance details: shift assignment, attendance type, half‑day toggle, punch‑in/out times, punch method, time‑strike violations, system/internal remarks, leave balances, and overtime calculations. The modal must support saving changes and recalculating attendance.
- **Current (SomyaHRMS):** No modal or edit functionality exists for the monthly view.
- **Missing/Issue:** Implement a Blade modal component `components/attendance-edit-modal.blade.php` and a route `AttendanceMonthlyController@update`. Use AJAX to submit the form and update the database (`attendance_dailies` and `attendance_daily_details`).

## 43. Bulk Attendance Upload/Export
- **Expected (Runtime):** An **Options** button provides Export (template), Upload (Excel file), and Download (attendance data) actions for the selected month and filters.
- **Current (SomyaHRMS):** No bulk upload/export feature for attendance.
- **Missing/Issue:** Add controller actions `exportTemplate`, `uploadExcel`, and `downloadAttendance` in `AttendanceMonthlyController`. Use `Maatwebsite\\Excel` package to handle Excel files.

## 44. Manual Attendance Overview & Settings
- **Expected (Runtime):** A page `Attendance > Manual Attendance` where HR can input monthly totals (Presents, Absents, Leaves, Week‑Offs, Holidays, etc.) for each employee. The top filters include Business Unit, Location, Cost Center, Department, Month Selector, and Employee Search. The system calculates payroll based on these totals instead of daily punches.
- **Current (SomyaHRMS):** No Manual Attendance UI, settings toggle, or backend logic exists.
- **Missing/Issue:** Create a view `resources/views/admin/attendance/manual_attendance.blade.php`, a controller `ManualAttendanceController@index` and `store` methods, and a settings flag in `attendance_settings` table (`manual_attendance_enabled`). Add UI to enable the feature via Setup > Attendance Settings.

## 45. Manual Attendance Settings Enablement
- **Expected (Runtime):** In Setup > Attendance Settings, an option `Enable Manual Attendance` allows toggling the feature on/off. When enabled, the Manual Attendance page becomes accessible.
- **Current (SomyaHRMS):** Settings page lacks this option.
- **Missing/Issue:** Add a boolean column `manual_attendance_enabled` to the `attendance_settings` migration, update the Settings Blade view to include a toggle, and handle saving via AJAX.

## 46. Manual Attendance Data Persistence & Payroll Integration
- **Expected (Runtime):** Upon saving monthly totals, the data is stored in a `manual_attendance` table and used by the payroll calculation engine for the selected month.
- **Current (SomyaHRMS):** No table or integration exists.
- **Missing/Issue:** Create migration for `manual_attendance` (employee_id, month, presents, absents, leaves, week_offs, holidays, etc.). In `PayrollController`, add logic to fetch manual totals when `manual_attendance_enabled` is true and use them instead of daily attendance records.

## 47. Salary Variable UI & Filters
- **Expected (Runtime):** A page `Data Capture > Salary-Variable` where admins can filter employees by Business Unit, Location, Department, and Variable Salary Type. The UI includes a Month Selector, Employee Search, and an *Arrear* checkbox. The table lists SN, Employee Name & Code, Location, Department, Amount (editable), Comments, Total, and action icons (Save, View).
- **Current (SomyaHRMS):** No Salary‑Variable page exists; there is no UI, filters, or table.
- **Missing/Issue:** Create a view `resources/views/admin/data_capture/salary_variable.blade.php` and a controller `SalaryVariableController@index` that loads employees, salary components, and renders the filter controls.

## 48. Salary Variable Save & Bulk Operations
- **Expected (Runtime):** The plus icon saves the entered amount via AJAX to `SalaryVariableController@store`. The Options button offers Export (Excel template), Upload (bulk import), and Download (current data). Non‑Cash Salary transfer functionality allows moving amounts from a non‑payable component to a payable one.
- **Current (SomyaHRMS):** No save endpoint, no bulk import/export, and no non‑cash salary logic.
- **Missing/Issue:** Implement routes `store`, `exportTemplate`, `uploadExcel`, `downloadData`, and `transferNonCash`. Use `Maatwebsite\\Excel` for Excel handling and add validation for component types and date ranges.

## 49. Deduction Variable UI & Filters
- **Expected (Runtime):** A page `Data Capture > Deductions` where admins can filter employees by Business Unit, Location, Department, and Deduction Type. The UI includes a Month Selector, Employee Search, and a table with columns: SN, Employee Name & Code, Location, Department, Amount (editable), Comments, Total, and action icons (Save, View).
- **Current (SomyaHRMS):** No Deduction‑Variable page exists; there is no UI, filters, or table.
- **Missing/Issue:** Create a view `resources/views/admin/data_capture/deductions.blade.php` and a controller `DeductionVariableController@index` that loads employees, deduction components, and renders the filter controls.

## 50. Deduction Variable Save & Bulk Operations
- **Expected (Runtime):** The plus icon saves the entered amount via AJAX to `DeductionVariableController@store`. The Options button offers:
  1. **Copy from Previous Period** – copies deduction data from the prior payroll month.
  2. **Download** – exports the displayed data to Excel.
  3. **Upload** – bulk imports deduction data from an Excel file.
- **Current (SomyaHRMS):** No save endpoint, no copy‑previous, no bulk import/export functionality.
- **Missing/Issue:** Implement routes `store`, `copyPrevious`, `exportExcel`, and `uploadExcel` in `DeductionVariableController`. Use `Maatwebsite\\Excel` for handling Excel files and ensure proper validation of deduction components and amounts.

## 51. Extra Days UI & Filters
- **Expected (Runtime):** A page `Data Capture > Extra Days` where admins can filter employees by Business Unit, Location, Department, and select a payroll month. The UI includes a Month Selector and an Employee Search field. The employee table shows Name, ID, Designation, Joining Date, Extra Days (input), Arrear Days (input), OT (input), Comments, and action icons (Save, View).
- **Current (SomyaHRMS):** No Extra Days page exists; there are no filters, table, or input fields.
- **Missing/Issue:** Create a view `resources/views/admin/data_capture/extra_days.blade.php` and a controller `ExtraDaysController@index` that loads employees and renders the filter controls and the data entry table.

## 52. Extra Days Save & Bulk Operations
- **Expected (Runtime):** The plus icon saves the entered values via AJAX to `ExtraDaysController@store`. The Options dropdown provides:
  1. **Download** – export the displayed data to Excel.
  2. **Upload** – bulk import extra days data from an Excel file.
- **Current (SomyaHRMS):** No save endpoint, no bulk import/export functionality.
- **Missing/Issue:** Implement routes `store`, `exportExcel`, and `uploadExcel` in `ExtraDaysController`. Use `Maatwebsite\\Excel` for handling Excel files and ensure validation of numeric inputs and month selection.

## 53. Extra Hours UI & Filters
- **Expected (Runtime):** A page `Data Capture > Extra Hours` where admins can filter employees by Business Unit, Location, Department, and select a payroll month. The UI includes a Month Selector and an Employee Search field. The employee table shows Name, ID, Designation, Deputation, Basic Hour Salary, Remarks, and action icons (Save). 
- **Current (SomyaHRMS):** No Extra Hours page exists; there are no filters, table, or input fields.
- **Missing/Issue:** Create a view `resources/views/admin/data_capture/extra_hours.blade.php` and a controller `ExtraHoursController@index` that loads employees and renders the filter controls and the data entry table.

## 54. Extra Hours Save & Operations
- **Expected (Runtime):** The save icon triggers an AJAX request to `ExtraHoursController@store` to persist the entered hours, remarks, and related data. No bulk import/export is defined for this module.
- **Current (SomyaHRMS):** No save endpoint exists.
- **Missing/Issue:** Implement route `store` in `ExtraHoursController` and the corresponding method to handle validation and storage of extra hours data.

## 55. OT Hours UI & Filters
- **Expected (Runtime):** A page `Data Capture > OT Hours` where admins can filter employees by Business Unit, Location, Department, and select a payroll month. The UI includes a Month Selector and an Employee Search field. The employee table displays Employee Name, ID, Designation, Joining Date, and an **OT (HH:MM)** input for manual adjustment, with an **Add or Deduct (+/-)** selector.
- **Current (SomyaHRMS):** No OT Hours page exists; filters, table, and adjustment fields are missing.
- **Missing/Issue:** Create a view `resources/views/admin/data_capture/ot_hours.blade.php` and a controller `OTHoursController@index` that loads employees and renders the filter controls and OT adjustment table.

## 56. OT Hours Save & Bulk Operations
- **Expected (Runtime):** The save icon (green disk) sends an AJAX request to `OTHoursController@store` to persist the adjusted OT values. The Options menu provides:
  1. **Download** – export the current OT data to Excel.
  2. **Upload** – bulk import OT adjustments from an Excel file.
- **Current (SomyaHRMS):** No save endpoint and no bulk import/export functionality.
- **Missing/Issue:** Implement routes `store`, `exportExcel`, and `uploadExcel` in `OTHoursController`. Use `Maatwebsite\Excel` for handling Excel files and ensure validation of time format (HH:MM) and add/deduct logic.

## 59. Salary Units UI & Filters
- **Expected (Runtime):** A page `Data Capture > Salary-Units` where admins can filter employees by Business Unit, Location, Department, and select a payroll month. The UI includes a Month Selector and an Employee Search field. The employee table displays SN, Employee Name & Code, Location, Department, Units (editable), Comments, Total, and action icons (Save, View).
- **Current (SomyaHRMS):** No Salary‑Units page exists; there are no filters, table, or input fields.
- **Missing/Issue:** Create a view `resources/views/admin/data_capture/salary_units.blade.php` and a controller `SalaryUnitsController@index` that loads employees, unit components, and renders the filter controls and data entry table.

## 60. Salary Units Save & Bulk Operations
- **Expected (Runtime):** The plus icon saves the entered units via AJAX to `SalaryUnitsController@store`. The Options dropdown provides:
  1. **Import From Travel** – upload travel distance data (e.g., Fuel Reimbursement) to calculate unit amounts.
  2. **Download** – export the displayed unit data to Excel.
  3. **Upload** – bulk import unit salary data from an Excel file.
- **Current (SomyaHRMS):** No save endpoint, no import/export functionality.
- **Missing/Issue:** Implement routes `store`, `importTravel`, `exportExcel`, and `uploadExcel` in `SalaryUnitsController`. Use `Maatwebsite\\Excel` for handling Excel files and ensure validation for numeric unit values and overwrite handling.

## 57. Loans UI & Filters
- **Expected (Runtime):** A page `Data Capture > Loans` where admins can filter employees by Business Unit, Location, Department, and select a payroll month. The UI includes a Month Selector and an Employee Search field. The employee table displays Employee Name, ID, Designation, Joining Date, and actions.
- **Current (SomyaHRMS):** No Loans page exists; filters, table, and loan actions are missing.
- **Missing/Issue:** Create a view `resources/views/admin/data_capture/loans.blade.php` and a controller `LoanController@index` that loads employees and renders the filter controls and loan list.

## 58. Loans Add / Edit / Delete & Bulk Operations
- **Expected (Runtime):** The "Add New Loan" button opens a modal to create a loan with fields: Employee, Loan Amount, Disbursement Date, Add in Salary (toggle), Deduction Start Date, Installments, Interest Method, etc. The loan schedule preview should be shown before saving. Edit and Delete icons allow modifying or removing loans. Bulk import/export via Options menu for loan data.
- **Current (SomyaHRMS):** No add/edit/delete UI, no preview, no bulk operations.
- **Missing/Issue:** Implement routes `store`, `update`, `destroy`, `exportExcel`, `uploadExcel` in `LoanController`. Use AJAX for actions and `Maatwebsite\\Excel` for bulk handling. Include validation for amounts, dates, installments, and interest calculations.

## 63. Leave Encashment UI & Filters
- **Expected (Runtime):** A page `Leave Management > Leave Encashment` where HR can filter by Location, Cost Center, Department, Employee, and select a payroll month. Additional filters include **Balance As On** (date), **Balance Above** (number of days), **Select Leave** (eligible leaves), and **Select Components** (salary components to encash). The UI displays an **Encashment Summary Table** with columns: Employee, Leave Balance, Daily Salary, Encashment Days, Encashment Amount.
- **Current (SomyaHRMS):** No Leave Encashment page exists; filters, summary table, and calculation logic are missing.
- **Missing/Issue:** Create a view `resources/views/admin/leave/encashment.blade.php` and a controller `LeaveEncashmentController@index` that loads employees, leave balances, salary components, and renders the filter controls and summary table.

## 64. Leave Encashment Processing & Bulk Operations
- **Expected (Runtime):** After configuring filters, HR can click **Calculate** to compute encashment amounts, then **Save** (green plus) to persist each record via AJAX to `LeaveEncashmentController@store`. Options menu provides:
  1. **Download** – export the encashment summary to Excel.
  2. **Upload** – import a prepared encashment Excel template.
  3. **Delete** – remove a specific encashment entry via a delete endpoint.
- **Current (SomyaHRMS):** No calculation, save, delete, or bulk import/export functionality.
- **Missing/Issue:** Implement routes `calculate`, `store`, `destroy`, `exportExcel`, `uploadExcel` in `LeaveEncashmentController`. Use `Maatwebsite\\Excel` for bulk handling and ensure validation of balance thresholds, selected leaves, and component mappings.

## 61. Leave Correction UI & Filters
- **Expected (Runtime):** A page `Leave Management > Leave Correction` where HR can filter employees by Business Unit, Location, Cost Center, Department, and select a payroll month and leave type. The UI includes a Month Selector, Leave Type dropdown, Employee Search field, and a table with columns: Employee (name & code), Designation, Opening (balance), Activity (earned/used), Correction (editable), Closing (calculated). Action icons include a Save (green disk) for each row.
- **Current (SomyaHRMS):** No Leave Correction page exists; filters, table, and correction fields are missing.
- **Missing/Issue:** Create a view `resources/views/admin/leave/leave_correction.blade.php` and a controller `LeaveCorrectionController@index` that loads employees, leave balances, and renders the filter controls and correction table.

## 62. Leave Correction Save & Bulk Operations
- **Expected (Runtime):** The Save icon sends an AJAX request to `LeaveCorrectionController@store` to persist the correction values and recalculate the closing balance. The Options menu provides:
  1. **Download** – export the displayed leave correction data to Excel.
  2. **Upload** – bulk import leave correction data from an Excel file.
- **Current (SomyaHRMS):** No save endpoint and no bulk import/export functionality.
- **Missing/Issue:** Implement routes `store`, `exportExcel`, `uploadExcel` in `LeaveCorrectionController`. Use `Maatwebsite\Excel` for handling Excel files and ensure validation of correction values and date ranges.

## 67. Employee Import UI & Template
- **Expected (Runtime):** A page `Bulk Updates > Employee Records` with a **Download Blank Template** button that provides an Excel file containing mandatory columns (Employee Code, First Name, Gender, Date of Joining) and optional columns (Location, Department, Designation, etc.). The UI also shows a **Master Values** button to auto‑create missing master data (locations, departments, designations) before import. Two checkboxes **Send Mobile Login Details** and **Send Web Login Details** appear before uploading.
- **Current (SomyaHRMS):** No Employee Import page, template download, or master‑value creation UI exists.
- **Missing/Issue:** Create a view `resources/views/admin/bulk/employee_import.blade.php` with buttons for **Download Blank Template**, **Master Values**, and **Upload Completed Template**. Implement a controller `EmployeeImportController@index` (view), `downloadTemplate`, `createMasters`, and `upload` actions.

## 68. Employee Import Processing & Error Handling
- **Expected (Runtime):** Uploading the completed Excel triggers server‑side validation: mandatory fields, date format (`YYYY‑MM‑DD`), gender (`M`/`F`), uniqueness of employee code. On success, a message *“All entries were successfully imported.”* appears. On failure, a detailed error list is returned indicating the row and reason (e.g., missing field, invalid date). The system must allow re‑upload after fixing errors without duplicating already imported records.
- **Current (SomyaHRMS):** No import endpoint, validation, or error feedback exists.
- **Missing/Issue:** Implement `EmployeeImportController@store` to read the Excel using `Maatwebsite\Excel`, perform validations, insert new employee records (excluding updates to Date of Joining), and optionally send login credentials based on the selected checkboxes. Return JSON with success flag or an array of error messages per row. Add routes `store`, `downloadTemplate`, `createMasters`. Ensure the migration for `employees` includes required columns and appropriate indexes.

## 65. Leave Types UI & Configuration
- **Expected (Runtime):** A page `Leave Management > Leave Type` listing all configured leave types with columns: Leave Name, Alias, Color, Paid Leave, Track Balance, Probation, and Actions (Edit, Policy, Delete). The page includes an **Add New** button that opens a modal to create a new leave type with fields: Leave Name, Short Name, Color, Probation Rule (Allow/Disallow), Paid Leave toggle, Maintain Leave Balance toggle, Allow Leave Requests toggle, Allow Future Requests toggle, Allow Advance Leaves, Requests Allowed for ___ Days in Past, Limit to ___ Leaves Every Month, and associated Policy configuration.
- **Current (SomyaHRMS):** No Leave Types page exists; there is no UI to list, add, or configure leave types, and the related database structures are missing.
- **Missing/Issue:** Create a view `resources/views/admin/leave/leave_types.blade.php` and a controller `LeaveTypeController@index` to display the list. Implement a modal view for adding/editing leave types (`resources/views/admin/leave/partials/leave_type_form.blade.php`). Add routes for `store`, `update`, `destroy`, and `policy` actions. Ensure the `leaves` table includes columns for name, alias, color, is_paid, track_balance, probation_allowed, etc.

## 66. Leave Types Policy & Grant/Lapse Management
- **Expected (Runtime):** Clicking **Policy** for a leave type opens a configuration page where admins can set:
  1. **Grant Leaves** – monthly grant amounts for each month, with optional attendance conditions (e.g., "If Presents ≥ X").
  2. **Reset Negative Balance Before Grant** toggle.
  3. **Lapse Leaves** – monthly lapse limits, with 999 meaning no lapse for the month.
  4. **Do Not Apply During/After Probation**, **Auto Apply**, **Fixed Grant** (on joining, on confirmation).
  5. **Recalculate Leaves** – tool to recalculate grants and lapses for a selected period, leaves, and optionally specific employees.
  6. **Delete Leave** – with replacement option for associated data.
- **Current (SomyaHRMS):** No policy configuration UI, no grant/lapse logic, no recalculate functionality, and no delete handling.
- **Missing/Issue:** Add a view `resources/views/admin/leave/leave_policy.blade.php` and controller methods `policy`, `savePolicy`, `recalculate`, `delete`. Implement backend logic to process grant/lapse based on the configured rules, using scheduled jobs or manual triggers. Use migrations to add necessary columns to `leave_policies` table and ensure proper foreign key relationships.


## 71. Employee Address UI & Template
- **Expected (Runtime):** A page `Bulk Updates > Employee Address` with a **Download Template** button providing an Excel file containing columns: Employee Code, Address Type, Address Line 1 (mandatory), Address Line 2, City, State, Zip, Country. The UI also displays an **Upload Completed Template** section with a file chooser and an **Upload** button. The first three rows of the template are reserved for system metadata and must remain unchanged.
- **Current (SomyaHRMS):** No Employee Address bulk update page, template download, or upload UI exists.
- **Missing/Issue:** Create a view `resources/views/admin/bulk/employee_address.blade.php` with **Download Template**, **Upload** controls, and a status message area. Implement a controller `EmployeeAddressController@index` (view), `downloadTemplate`, and `upload` actions.

## 72. Employee Address Processing & Validation
- **Expected (Runtime):** Uploading the Excel triggers server‑side validation: required fields (Employee Code, Address Type, Address Line 1), valid employee codes, and allowed address types. On success, the system inserts new address records or updates existing ones, then displays a success toast *“All address records have been updated.”*. On failure, a detailed error list per row is returned (e.g., missing mandatory field, invalid employee code). The process must be idempotent—re‑uploading a corrected file updates only the rows with errors.
- **Current (SomyaHRMS):** No import endpoint, validation, or error feedback exists.
- **Missing/Issue:** Implement `EmployeeAddressController@store` to read the Excel via `Maatwebsite\Excel`, validate each row, upsert address records, and return JSON with success status or error details. Add routes `store`, `downloadTemplate`. Ensure the `employee_addresses` table migration includes columns for `employee_id`, `address_type`, `address_line1`, `address_line2`, `city`, `state`, `zip`, `country`.

## 73. Bank Details UI & Filters
- **Expected (Runtime):** A page `Bulk Updates > Bank Details` with filter dropdowns for Business Unit, Location, Department, and a **Search Employee** field. After applying filters and clicking **View**, a table lists employees with editable fields: Bank Name, IFSC Code, Account Number, and a **Save** icon per row. An informational note warns that updating verified bank details will reset verification status.
- **Current (SomyaHRMS):** No Bank Details bulk update page, filters, or editable table exists.
- **Missing/Issue:** Create a view `resources/views/admin/bulk/bank_details.blade.php` containing the filter controls, employee list, and inline edit fields with save actions. Implement a controller `BankDetailsController@index` to load employees based on filters and render the page.

## 74. Bank Details Bulk Update Processing
- **Expected (Runtime):** Clicking the **Save** icon sends an AJAX request to `BankDetailsController@update` to persist bank information. The backend updates the `employee_bank_details` table, clears any existing verification flag, and returns a success toast indicating the number of records updated. Errors (e.g., invalid IFSC format) are reported per employee.
- **Current (SomyaHRMS):** No update endpoint, transaction handling, or verification reset logic exists.
- **Missing/Issue:** Implement `BankDetailsController@update` to handle bulk updates within a DB transaction, reset verification status, and log changes. Add routes `update`. Ensure the `employee_bank_details` migration includes `bank_name`, `ifsc_code`, `account_number`, `is_verified` columns.

## 69. Employee Options UI & Filters
- **Expected (Runtime):** A page `Bulk Updates > Employee Options` with filter dropdowns for Business Unit, Location, Cost Center, Department, Designation, and a **Select All Employees** toggle. After applying filters, the UI shows the total count of selected employees.
- **Current (SomyaHRMS):** No Employee Options page exists; filters and bulk update UI are missing.
- **Missing/Issue:** Create a view `resources/views/admin/bulk/employee_options.blade.php` containing the filter controls and a list of configurable options (Leave policy, Salary calculation rules, Statutory deductions, etc.) with ON/OFF toggles. Implement a controller `EmployeeOptionsController@index` to load employees based on filters and render the options page.

## 70. Employee Options Bulk Update Processing
- **Expected (Runtime):** After selecting employees and toggling desired options, a **Apply Changes** button sends an AJAX request to `EmployeeOptionsController@updateBulk`. The backend updates the selected employees' records in one transaction, applying leave policy settings, salary calculation flags, and statutory deduction configurations. A success toast confirms the number of records updated; errors are reported per employee if any validation fails.
- **Current (SomyaHRMS):** No bulk update endpoint, no transaction handling, and no feedback mechanism.
- **Missing/Issue:** Implement `EmployeeOptionsController@updateBulk` to process the bulk updates, ensure atomicity with DB transactions, and log changes for audit. Add routes `updateBulk` and corresponding request validation. Ensure UI disables the Apply button while processing and displays success/error messages.

## 75. Salary Details UI & On‑Screen Entry
- **Expected (Runtime):** A page `Bulk Updates > Salary Details` where HR can select employees via filters (Business Unit, Location, Department, Designation) and then enter component amounts directly in a table. Columns include Basic, HRA, MA, Deductions, and any other applicable salary components. Each row has a **Save** icon that sends an AJAX request to `SalaryDetailsController@updateComponent` to persist the value instantly, reflecting the change in the employee’s record.
- **Current (SomyaHRMS):** No Salary Details bulk update page, no on‑screen entry table, and no save endpoint.
- **Missing/Issue:** Create a view `resources/views/admin/bulk/salary_details.blade.php` with filter controls and an editable component table. Implement `SalaryDetailsController@index` (view) and `updateComponent` (AJAX) actions. Add routes `index` and `updateComponent`.

## 76. Salary Details Excel Upload & Bulk Revision
- **Expected (Runtime):** The page provides an **Options** menu with **Download** to obtain a salary template Excel file (including all salary components) and **Upload** to submit a completed file. Upload triggers `SalaryDetailsController@import` which validates mandatory fields, component values, and employee codes, then upserts salary records. A success toast *“All salary details have been updated.”* appears; errors are returned per row. Additionally, an **Add Bulk Revision** button opens a modal to define a common revision (e.g., increment percentage) that applies to selected employees via `SalaryDetailsController@applyRevision`.
- **Current (SomyaHRMS):** No template download, upload endpoint, validation, or bulk revision functionality.
- **Missing/Issue:** Implement `SalaryDetailsController@downloadTemplate`, `import`, and `applyRevision` actions. Use `Maatwebsite\Excel` for handling files, ensure atomic DB transactions, and add routes `downloadTemplate`, `import`, `applyRevision`. Create migrations for `employee_salary_details` if not present, with columns for each component.

## 79. Work Profile UI \u0026 Filters
- **Expected (Runtime):** A page `Bulk Updates > Work Profile` with filter dropdowns for Business Unit, Location, Cost Center, Department and a checkbox **Only records without an active profile**. After applying filters and clicking **View**, a table lists employees with editable fields: Location, Cost Center, Department, Grade, Designation, Shift Policy, Week‑Off Policy. Each row has a **Save** icon that sends an AJAX request to `WorkProfileController@update` to persist the changes instantly, updating the employee’s latest work profile record.
- **Current (SomyaHRMS):** No Work Profile bulk‑update page, filter UI, or on‑screen edit functionality exists.
- **Missing/Issue:** Create a view `resources/views/admin/bulk/work_profile.blade.php` containing the filter controls and an editable table for the fields above. Implement `WorkProfileController@index` (view) and `update` (AJAX) actions. Add routes `index` and `update`.

## 80. Work Profile Excel Upload \u0026 Bulk Revision
- **Expected (Runtime):** The page provides an **Options** menu with **Download** to obtain a work‑profile Excel template (including columns for Employee Code, Location, Cost Center, Department, Grade, Designation, Shift Policy, Week‑Off Policy) and **Upload** to submit a completed file. Upload triggers `WorkProfileController@import` which validates mandatory fields, employee codes, and allowed values, then upserts work‑profile records. A success toast *“All work profiles have been updated.”* appears; errors are returned per row. An **Add Bulk Revision** button opens a modal to define a common revision (e.g., change Shift Policy for selected employees) that applies via `WorkProfileController@applyRevision`.
- **Current (SomyaHRMS):** No template download, upload endpoint, validation, or bulk revision functionality.
- **Missing/Issue:** Implement `WorkProfileController@downloadTemplate`, `import`, and `applyRevision` actions using `Maatwebsite\\Excel`. Add routes `downloadTemplate`, `import`, `applyRevision`. Ensure a migration for `employee_work_profiles` includes columns for the relevant attributes.

## 77. Salary Deductions UI \u0026 On‑Screen Update
- **Expected (Runtime):** A page `Bulk Updates > Salary Deductions` with filter dropdowns for Business Unit, Location, Cost Center, Department and a **View** button. The resulting table lists employees with a **Deduction** column where the fixed deduction amount can be entered directly. Each row includes a **Save** icon that sends an AJAX request to `SalaryDeductionsController@update` to persist the value instantly, updating the employee’s salary record in real time.
- **Current (SomyaHRMS):** No Salary Deductions bulk‑update page, on‑screen entry table, or save endpoint.
- **Missing/Issue:** Create a view `resources/views/admin/bulk/salary_deductions.blade.php` with filter controls and an editable deduction column. Implement `SalaryDeductionsController@index` (view) and `update` (AJAX) actions. Add routes `index` and `update`.

## 78. Salary Deductions Excel Upload \u0026 Bulk Update
- **Expected (Runtime):** An **Options** menu provides **Download** to obtain a salary deductions Excel template (Employee Code, Deduction Component, Amount) and **Upload** to submit a completed file. Upload triggers `SalaryDeductionsController@import` which validates mandatory fields, employee codes, and deduction amounts, then upserts records. A success toast *“All salary deductions have been updated.”* appears; errors are returned per row. The process must be idempotent, allowing re‑upload after fixing errors.
- **Current (SomyaHRMS):** No template download, upload endpoint, or validation logic.
- **Missing/Issue:** Implement `SalaryDeductionsController@downloadTemplate` and `import` actions using `Maatwebsite\\Excel`. Add routes `downloadTemplate` and `import`. Ensure a migration for `employee_salary_deductions` includes columns `employee_id`, `deduction_component`, `amount`.
The above content does NOT show the entire file contents. If you need to view any lines of the file which were not shown to complete your task, call this tool again to view those lines.

## 81. Biometric Codes UI \u0026 Filters
- **Expected (Runtime):** A page `Bulk Updates > Biometric Codes` with filter dropdowns for Business Unit, Location, Cost Center, Department, Employee Name, and Biometric Code, plus a **Search** button. After applying filters, a table lists matching employees with an editable **Biometric Code** field for each row.
- **Current (SomyaHRMS):** No Biometric Codes page, filter UI, or editable fields exist.
- **Missing/Issue:** Create a view `resources/views/admin/bulk/biometric_codes.blade.php` containing filter controls and a table with editable biometric code inputs. Implement `BiometricCodesController@index` (view) and `update` (AJAX) actions. Add routes `index` and `update`.

## 82. Biometric Codes Bulk Update Processing
- **Expected (Runtime):** Editing a biometric code and saving triggers an AJAX request to `BiometricCodesController@update` which validates uniqueness and format, updates the employee record, and returns a success toast. Errors (e.g., duplicate codes) are reported per employee.
- **Current (SomyaHRMS):** No update endpoint, validation, or uniqueness enforcement exists.
- **Missing/Issue:** Implement `BiometricCodesController@update` with server‑side validation (unique per employee, matches device requirements). Use DB transactions for bulk saves and log changes. Add route `update`. Ensure the `employees` table has a `biometric_code` column with a unique index.

# Dashboard Overview - Prompts for 100% Similarity

Copy and paste the prompts below to instruct the AI to make your SomyaHRMS project exactly match the Runtime Software for the Dashboard module.

---

### Prompt 1: Fix Employee Summary (Max Employee Limit)
"Update the `HomeController@root` and `resources/views/index.blade.php`. In the Employee Summary section, please add support for showing the 'Maximum employee limit as per the subscribed plan'. If we don't have a subscription table yet, please add a `max_employees` column to the `businesses` table, fetch it in the controller, and display it in the 'Employees' card on the dashboard next to the active/inactive employee count."

### Prompt 2: Fix Active Mobile Users (Last 30 Days Rule)
"In `HomeController@root`, the `$activeMobileUsers` variable is currently just counting people who have `mobile_login = 1`. Please update this query to also check the employee's last login activity (using our existing session or activity log tables) so it only counts employees who have actively used the mobile application in the **last 30 days**."

### Prompt 3: Add Subscription Status Section
"We need to add a brand new 'Subscription Status' card to the dashboard. Please update `resources/views/index.blade.php` to include a new card showing 'Account Validity' and 'Due Date' (Renewal timelines). Also, update `HomeController@root` to pass dummy subscription data for now, and instruct me on how we will pull the real subscription data from the database."

### Prompt 4: Fix Attendance Trend (Percentages instead of Absolute Numbers)
"Currently, our Attendance Trend graph in `index.blade.php` shows absolute numbers for Present, Absent, Half Day, etc. The Runtime Software requires this graph to show **percentages** (Present percentage, Absent percentage, Leave percentage). Please update the logic in `HomeController@root` (around line 113 to 286) to calculate percentages, and update the ApexCharts configuration in `index.blade.php` to display these percentages instead."

### Prompt 5: Fix Open Requests (Clickable Redirects)
"In `resources/views/index.blade.php`, the 'Open Requests' section shows Missed Punches, Help Desk, and Leaves, but they are just plain text. Please wrap these items in anchor (`<a>`) tags so that clicking on them redirects the user to the `hr.requests.attendance`, `hr.requests.helpdesk`, and `hr.requests.leave` routes respectively."

### Prompt 6: Fix Upcoming Events (Open Report Link)
"In `resources/views/index.blade.php`, go to the 'Upcoming Events' card. Please add an 'Open Report' link/button at the top right of the card header (or bottom of the card) that redirects the user to the `employee_event` report route so HR can view all future events."

---

# Attendance Dashboard - Prompts for 100% Similarity

### Prompt 7: Fix Early Goers Logic
"In `AttendanceDashboardController@index`, the Early Goers data is currently mocked (`$earlyGoersData = [$presentCount + $halfDayCount, 0];`). Please update this logic to accurately calculate early goers by checking the `AttendanceDaily` or `AttendanceDailyDetail` table to see if an employee's punch out time is earlier than their shift end time, and pass the correct data array to the view."

### Prompt 8: Build Detailed Employee Listing Tables & Tabs
"In `resources/views/dashboards/attendance.blade.php`, we currently only have a single table for 'ABSENTS'. Please convert this section into a tabbed interface (e.g., using Bootstrap nav-tabs) with tabs for: 'Absentees', 'On Leave/Week Off', 'Late Comers', and 'Early Goers'. 
Also, update `AttendanceDashboardController@index` to fetch the employee lists for Leaves, Late Comers, and Early Goers (just like it does for `$topAbsentees`) and pass them to the view. Make sure to fetch the actual Shift Name and real Time In/Out data to replace the hardcoded 'Standard Shift' and '-'."

---

# Flight Risk Assessment - Prompts for 100% Similarity

### Prompt 9: Build the Flight Risk Backend & Calculation Logic
"We need to implement the 'Flight Risk' module from scratch since it's entirely missing. First, create a migration to add `flight_risk_status` (Enum: No Risk, Moderate Risk, High Risk, Untracked, Uncalculated), `flight_risk_score` (integer), and `last_risk_calculated_at` (timestamp) to the `employees` table (or a dedicated `flight_risks` table). Next, create a Laravel Console Command (e.g., `CalculateFlightRisk`) that runs every 7 days to evaluate employee attendance irregularities over the past month and updates these columns automatically."

### Prompt 10: Build the Flight Risk Dashboard UI
"Create a new `FlightRiskController` and a route (e.g., `admin/flight-risk`). Then, build the `flight-risk.blade.php` view. The UI should display clickable cards for 'No Risk', 'Moderate Risk', 'High Risk', 'Untracked', and 'Uncalculated' with their respective employee counts. Below the cards, add a table that dynamically loads the employees belonging to the clicked category, displaying their Name, Deputation, Risk Score, and Last Updated Date. Also, add an action button in the table to allow managers to manually set an employee to 'Untracked'."

---

# Org Chart - Prompts for 100% Similarity

### Prompt 11: Add Org Chart Search & Filter
"Update the `resources/views/admin/employee/org-chart.blade.php` view and `OrgChartController@index`. Add a dropdown at the top of the page (using Select2) that allows the user to search for and select a specific 'Reporting Manager'. When a manager is selected, filter the `$employees` collection in the controller to only include that manager and all their direct/indirect subordinates so the chart renders with the selected manager at the root, instead of dumping all employees into the chart at once."

---

# All Employees - Prompts for 100% Similarity

### Prompt 12: Fix Employee List Table Data & Columns
"Update `resources/views/admin/employee/index.blade.php`. 
1. Make the Employee Name clickable, linking to `route('employee.profile.summary', $employee->id)`. 
2. Add a 'Location' column next to 'Department' and fetch it from `$employee->workProfiles->first()->location->name`. 
3. Fix the Status badge so it dynamically checks `$employee->status` instead of hardcoding 'Active'. 
4. For inactive employees, display their 'Exited Date' (add this column to the `employees` table migration if missing). 
5. Add a 'Confirm' link badge if the employee's confirmation is pending."

### Prompt 13: Add Missing Actions to Dropdown
"In the `resources/views/admin/employee/index.blade.php` Actions dropdown, please add two new items: 'Download Statement' and 'Deactivate'. Update `EmployeeController` with routes and logic to handle downloading a PDF statement of the employee, and a toggle to deactivate (or activate) the employee."

### Prompt 14: Add Employee Confirmation Notifications
"In `resources/views/admin/employee/index.blade.php`, add a Bootstrap alert banner at the top of the page (below the breadcrumb) that checks if there are any pending employee confirmations and displays: 'Employee confirmation updates available.' with a 'Read More' link."

---

# Employee Overview (Profile) - Prompts for 100% Similarity

### Prompt 15: Implement BG Check Module
"In the Employee Profile section, the 'BG Check' tab is currently a dead link (`href='#'`). Please implement this module. 
1. Create a migration for `employee_bg_checks` with fields like `employee_id`, `check_type`, `agency_name`, `status` (Pending/Cleared/Failed), and `remarks`. 
2. Create an `EmployeeBgCheckController` and a new route group inside the `employee.profile.` prefix. 
3. Build the Blade view to list existing background checks and a form to add/update new checks for the employee. Update the sidebar link in `layout.blade.php` to point to this new route."

### Prompt 16: Implement Deactivate/Offboarding Module
"In the Employee Profile section, the 'Deactivate/Delete' tab is a dead link. Please implement this page.
1. Create a `DeactivateController` and add the route to the profile group.
2. In the controller, check and list all dependencies for the employee (e.g., any Assets they currently hold, any Employees reporting to them, any Pending Requests they are supposed to approve).
3. Build the Blade view to display these dependencies as warnings. Add a final 'Deactivate Employee' form that updates their status to 'inactive' and sets an 'Exited Date'. Update the sidebar link in `layout.blade.php`."

---

# Onboarding Dashboard - Prompts for 100% Similarity

### Prompt 17: Build the Onboarding Analytics Dashboard
"Update the `OnboardingController@index` and `resources/views/admin/employee/onboarding/index.blade.php`. 
Transform this page into a real dashboard:
1. Add a Date Range filter at the top.
2. Add four Key Metric cards below the header: 'Total Forms', 'Offers Sent', 'In Progress', and 'Total Hires'. 
3. Integrate ApexCharts to show three sections: 'Onboarding Overview' (completion %), 'Hires by Department' (distribution %), and a 'Hiring Trend' graph (month-wise joinings). 
4. Update the controller to calculate and pass these analytics based on the selected date filter."

### Prompt 18: Implement Onboarding Forms & Action Buttons
"In `resources/views/admin/employee/onboarding/index.blade.php`, update the Quick Action buttons to include 'New Onboarding', 'Onboarding Forms', and a 'More' dropdown containing 'Bulk Onboarding', 'Dynamic Letter', 'Approve Additional', and 'Onboarding Settings'. 
Next, create a migration for an `onboarding_forms` table to track form status ('Draft', 'Sent', 'Approved'). Finally, update the middle data table in the view to list these Onboarding Forms with their statuses, rather than just listing raw employee records."

---

# Add New Employee - Prompts for 100% Similarity

### Prompt 19: Add Missing Fields to Add Employee Form
"Update `resources/views/admin/employee/onboarding/create.blade.php` and `OnboardingController@store`.
1. Add 'Gender' (dropdown: Male/Female/Other) and make it required.
2. Change 'Phone Number' to 'Mobile Number' and make it required.
3. Add 'Confirmation Date' (date) and 'Date of Birth' (date) as optional fields."

### Prompt 20: Add Work Profile & Policies Side-Section
"In `resources/views/admin/employee/onboarding/create.blade.php`, restructure the layout to have a main column for Basic Info, and a side-section (or right column) for 'Work Profile' and 'Policies'.
1. Under 'Work Profile', add dropdowns for Location, Cost Centre, Department, Grade, and Designation. 
2. Under 'Policies', add dropdowns for 'Shift Policy' and 'Week Off Policy'.
3. Update `OnboardingController@create` to fetch these new models (`Location`, `CostCenter`, `Grade`, `Shift`, `WeekOffPolicy`) and pass them to the view.
4. Update `OnboardingController@store` to save these values into the corresponding `work_profiles` and `employee_policies` tables/relations when the employee is created."

### Prompt 21: Add Self-Service Access Checkboxes
"In `resources/views/admin/employee/onboarding/create.blade.php`, add a new section at the bottom called 'Employee Self-Service Access' with two checkboxes: 'Send Mobile Login' and 'Send Web Login'. 
Then, update `OnboardingController@store` to check if these inputs are selected. If they are, fire an event or dispatch a Mail/SMS notification (you can just add a `// TODO: Send Email` comment for now) to send the employee their generated credentials, instead of just silently creating the user record without notifying them."

---

# Onboarding Forms (Self-Onboarding) - Prompts for 100% Similarity

### Prompt 22: Build the Multi-Part Onboarding Form Wizard
"Create a new controller `OnboardingFormController` and a new set of views (`resources/views/admin/employee/onboarding-forms/create.blade.php`). Build a 3-step wizard UI using Bootstrap/JS or Laravel Livewire:
1. **Part A (Candidate Details):** Fields for Name, Email, Mobile. Add checkboxes for PAN, Aadhaar, and Bank verification.
2. **Part B (Attach Policies):** Display a multi-select or list of company policies (from `policies` table) that can be attached.
3. **Part C (Offer Letter & Salary):** Inputs to define salary structure and upload an offer letter PDF.
Save this data progressively into a new `onboarding_forms` table. Add 'Continue' and 'Skip' buttons where applicable according to standard wizard flows."

### Prompt 23: Implement 'Finalize & Send' and Candidate Portal
"In the final step of the Onboarding Form Wizard, add a toggle for 'Send Form'. 
1. When Finalized, if 'Send Form' is true, generate a unique secure token for this `onboarding_form` and send an email/SMS invitation to the candidate's email with the link. Mark status as 'Sent'. If false, mark as 'Draft'.
2. Create a public-facing Candidate Portal (`CandidateOnboardingController`) that validates the unique token from the email link. This portal should allow the candidate to view their Offer Letter, read the attached Policies, fill out their full Employee details (Addresses, Identity, Family), and finally 'Submit' the form for HR approval."

---

# Onboarding Forms Management - Prompts for 100% Similarity

### Prompt 24: Build the Onboarding Forms List View & Filters
"Create a new view `resources/views/admin/employee/onboarding-forms/index.blade.php`. 
1. Add a 'Form Status' dropdown filter (All, Draft, Sent, Submitted, Approved, Rejected) and a 'Load' button that filters the data.
2. Build a DataTables grid with columns: ID, Candidate (Name & Creation Date), Contact Details (Email & Mobile), Info (Add a 'View Form' link and 'Offer Letter' link), Status (with appropriate colored badges), and Actions. 
3. Update `OnboardingFormController@index` to serve this view and handle the filtering."

### Prompt 25: Implement Form Actions (Recall, Take Action, Delete)
"In the `OnboardingFormController`, implement the following methods and add their action buttons in the `index.blade.php` view:
1. **Edit Form**: Only show this button if status == 'Draft'.
2. **Recall Form**: Show this button if status == 'Sent'. This method should change the form status back to 'Draft'.
3. **Take Action**: Show this button if status == 'Submitted'. Clicking it should open a Bootstrap modal allowing HR to choose 'Approve' (finalizes the employee record), 'Send Again' (changes status back to 'Sent' for corrections), or 'Reject' (changes status to 'Rejected').
4. **Delete**: Adds a delete button with a JS confirmation alert to permanently remove the `onboarding_form` record."

---

# Bulk Onboarding - Prompts for 100% Similarity

### Prompt 26: Build the Bulk Onboarding UI
"Create a new view `resources/views/admin/employee/onboarding/bulk.blade.php` and map it to a new `BulkOnboardingController@index`. 
1. The UI should have two input methods at the top: a standard inline form (Name, Email, Mobile) with an 'Add (+)' button, and a 'Bulk Add' button that opens a modal with a `<textarea>` where HR can paste comma/tab-separated records (Name, Email, Mobile).
2. Write JavaScript to parse these inputs and append them to an HTML table (temporary list) on the page. Ensure the JS limits the total count to 25 candidates.
3. Below the table, add checkboxes for 'Mobile Verification', 'PAN Verification', 'Bank Verification', and 'Aadhaar Verification'."

### Prompt 27: Implement Batch Form Sending Logic
"In the `bulk.blade.php` view, wrap the temporary list and verification checkboxes in a form that submits to `BulkOnboardingController@store`.
1. The JS must inject hidden input arrays (e.g., `candidates[0][name]`, `candidates[0][email]`) for every candidate in the temporary table so they are posted to the backend.
2. In the `BulkOnboardingController@store` method, loop through the `candidates` array. For each candidate, create a record in the `onboarding_forms` table (setting the requested verification flags).
3. Dispatch an email/SMS invitation to each candidate containing their unique secure self-onboarding link, just like the single form wizard. Return a success message with the number of forms sent."

---

# Onboarding Form Settings - Prompts for 100% Similarity

### Prompt 28: Build the Onboarding Settings UI & Auto-Save (AJAX)
"Create a new view `resources/views/admin/employee/onboarding/settings.blade.php` and map it to `OnboardingSettingsController@index`. 
1. Build a UI with categorized toggle switches/checkboxes for 'Personal Information', 'Statutory Details', 'Family Details', 'Addresses', and 'Documents' to mark them as 'Required'. 
2. Create a migration for `onboarding_settings` (with a column for the setting `key` and boolean `is_required`). 
3. Implement AJAX logic in the view so that whenever a checkbox is clicked, it instantly triggers a POST request to `OnboardingSettingsController@update` to save the setting without needing a 'Save' button."

### Prompt 29: Enforce Dynamic Validation in Candidate Portal
"Update the `CandidateOnboardingController` (the public self-onboarding portal we created earlier). 
When the candidate attempts to submit their self-onboarding form, fetch the rules from the `onboarding_settings` table. Dynamically add these fields to the Laravel validation rules array. For example, if the setting for 'bank_details' is `is_required = true`, ensure the controller returns a validation error if the candidate leaves it blank, blocking the form submission until they provide it."

---

# Employee Separation - Prompts for 100% Similarity

### Prompt 30: Build the Employee Separation Database & Logic
"Create a migration for an `employee_separations` table to track exits. It should include fields: `employee_id`, `exit_date`, `exit_reason` (Resignation, Termination, etc.), `retention_attempted` (boolean), and `status` (Pending, Approved). 
Next, create a `SeparationDashboardController@index` that accepts a Date Range filter from the request. In this method, calculate: 1) Total Exits, 2) Average Tenure (using the employee's joining date to exit date), 3) Retention Attempts %, and 4) Pre-Confirmation exits."

### Prompt 31: Build the Separation Dashboard UI (Charts & Tables)
"Create a new view `resources/views/admin/separation/dashboard.blade.php`. 
1. Display the four calculated key metrics at the top. 
2. Integrate ApexCharts to render: a Pie Chart for 'Top Exit Reasons', a Bar Chart for 'Exit Trend' (monthly exits), and a grouped breakdown for 'Tenure Analysis' (Below 6m, 6-24m, 2-5y, 5y+).
3. Include donut charts for Exits by Department, Business Unit, and Location.
4. Below the charts, add a DataTables grid to display 'Recently Logged / Pending Exits' fetching from the `employee_separations` table."

---

# Initiate Exit (Employee Separation) - Prompts for 100% Similarity

### Prompt 32: Build the 'Initiate Exit' Form & AJAX Loader
"Create a new view `resources/views/admin/separation/create.blade.php` and map it to `SeparationController@create`. 
1. Build a two-column layout. The left column contains the form: Employee Dropdown, 'Load Details' button, Resignation Date, Last Working Date, Reason Dropdown, Remarks, and a 'Trying to Retain' checkbox. 
2. The right column should be an 'Employee Summary' card (Name, DOJ, Designation, Dept, Location).
3. Write JavaScript/AJAX so that when 'Load Details' is clicked, it fetches the selected employee's data from a new endpoint `SeparationController@getEmployeeDetails`, populates the right-side summary, and auto-calculates the 'Notice Period' based on their policy."

### Prompt 33: Integrate Flight-Risk Alerts & Store Logic
"In `SeparationController@create`, query the `employees` (or `flight_risks`) table to find employees where `flight_risk_status == 'High Risk'`. Pass this list to `create.blade.php` and display it as an alert/banner at the very top of the page. 
Next, implement `SeparationController@store`. When the form is submitted, insert the data into the `employee_separations` table we created earlier, setting the initial status to 'Initiated' or 'Pending'."

---

# Pending Exit - Prompts for 100% Similarity

### Prompt 34: Build the Pending Exits Grid & Filters
"Create a new view `resources/views/admin/separation/pending.blade.php` mapped to `SeparationController@pending`. 
1. Build a top filter bar with inputs for 'Exit Date From/To', a dropdown for 'Exit Reason', and a Search box. 
2. Build a DataTables grid displaying: Name & Code, DOJ, Resignation Date, Exit Date, 'Planned vs Actual Notice', and Exit Reason. 
3. The controller should fetch records from `employee_separations` where the status is 'Initiated' or 'Pending'."

### Prompt 35: Implement F&F Processing and Finalize Actions
"In `SeparationController`, add methods for `cancelExit`, `processExit`, and `finalizeExit`. 
1. **Cancel**: Deletes or marks the separation as cancelled.
2. **Process**: In the UI, clicking 'Process' should trigger a SweetAlert/JS confirm: 'Make sure you have completed Leave encashment & Gratuity'. Upon confirmation, the backend calculates F&F (you can mock this logic for now) and updates the status to 'Processed'.
3. **Finalize**: For 'Processed' exits, show a 'Finalize Exit' button. Clicking this should open a modal allowing HR to generate/download 'Relieving Letters' and click a button to 'E-Mail to Employee', officially marking the exit as complete."

---

# Ex-Employees (Separation) - Prompts for 100% Similarity

### Prompt 36: Build the Ex-Employees List View
"Create a new view `resources/views/admin/separation/ex-employees.blade.php` mapped to `SeparationController@exEmployees`. 
1. Build a top filter bar with inputs for 'Exit Date From/To', a dropdown for 'Exit Reason', and a Search box. 
2. Build a DataTables grid displaying: Name & Code, DOJ, DOR (Resignation Date), DOE (Exit Date), Notice Period (Planned vs Served), and Exit Reason. 
3. The controller should fetch records from `employee_separations` where the status is 'Finalized' or 'Processed'."

### Prompt 37: Implement Post-Exit Actions & Deactivation
"In `SeparationController`, implement the following methods and add them to the Actions dropdown in the `ex-employees.blade.php` view:
1. **Download FnF**: Generates a PDF of the Full & Final settlement.
2. **Re-process Exit**: Changes status back to 'Pending' to recalculate.
3. **Re-hire**: Updates the employee status back to active and clears the separation record.
4. **Delete**: Soft deletes the `employee_separations` record.
5. **Deactivate**: Redirects to the Deactivation page (which we defined in Prompt 16). Ensure the Deactivation page checks for active subordinates, pending requests, or held assets. Add a 'Replace' and 'Replace All' dropdown UI on the Deactivation page to reassign these dependencies to another active employee before finally setting the exiting employee to 'inactive'."

---

# Daily Punches (Attendance) - Prompts for 100% Similarity

### Prompt 38: Fix Daily Punches Filters & Badges
"In `resources/views/admin/employee/attendance/daily_punches.blade.php`, please add a 'Month Selector' input next to the date picker. Additionally, update the 'Half Day' attendance badge logic in the table. Currently, it outputs 'H' (`<span class="badge bg-warning fs-12">H</span>`); please change this to 'PA' to perfectly match the Runtime Software."

### Prompt 39: Implement 'View' and 'Edit' Action Modals
"In the Daily Punches table (`daily_punches.blade.php`), the 'View' and 'Edit' action buttons are currently dead links (`href='#'`). 
1. Build a 'View Punch Details' Bootstrap modal that opens when the eye icon is clicked. Use AJAX to fetch and display the exact punch-in and punch-out logs (from `AttendanceDailyDetail` table) for that employee on that date.
2. Build an 'Edit Attendance' Bootstrap modal that opens when the edit icon is clicked. It should contain inputs to manually overwrite the 'Start Time', 'End Time', and 'Attendance Status'. Submit this to a new `AttendanceDailyController@updatePunches` method to update the employee's `attendance_dailies` record."

---

# Daily Attendance - Prompts for 100% Similarity

### Prompt 40: Fix Daily Attendance UI Details
"In `resources/views/admin/employee/attendance/daily.blade.php`:
1. Add a 'Month Selector' next to the existing date picker.
2. In the Employee Card Header, add the employee's `Designation` below their Name and Employee Code.
3. Fetch the `punch_method` (e.g., QR Code, Biometric) from the `AttendanceDailyDetail` table and display an icon/badge for it next to the Punch In/Out times in the timeline."

### Prompt 41: Wire Up the Action Buttons
"In `resources/views/admin/employee/attendance/daily.blade.php`, the 'View' and 'Edit' buttons at the right of each card are inactive. Please attach `data-bs-toggle='modal'` attributes to them and reuse the 'View Punch Details' and 'Edit Attendance' modals we built for the 'Daily Punches' module so HR can view and edit records from this timeline view as well."

# Salary Variable - Testing & Comparison Report

## 47. Salary Variable UI & Filters
- **Expected (Runtime):** A page `Data Capture > Salary-Variable` where admins can filter employees by Business Unit, Location, Department, and Variable Salary Type. The UI includes a Month Selector, Employee Search, and an *Arrear* checkbox. The table lists SN, Employee Name & Code, Location, Department, Amount (editable), Comments, Total, and action icons (Save, View).
- **Current (SomyaHRMS):** No Salary‑Variable page exists; there is no UI, filters, or table.
- **Missing/Issue:** Create a view `resources/views/admin/data_capture/salary_variable.blade.php` and a controller `SalaryVariableController@index` that loads employees, salary components, and renders the filter controls.

## 48. Salary Variable Save & Bulk Operations
- **Expected (Runtime):** The plus icon saves the entered amount via AJAX to `SalaryVariableController@store`. The Options button offers Export (Excel template), Upload (bulk import), and Download (current data). Non‑Cash Salary transfer functionality allows moving amounts from a non‑payable component to a payable one.
- **Current (SomyaHRMS):** No save endpoint, no bulk import/export, and no non‑cash salary logic.
- **Missing/Issue:** Implement routes `store`, `exportTemplate`, `uploadExcel`, `downloadData`, and `transferNonCash`. Use `Maatwebsite\Excel` for Excel handling and add validation for component types and date ranges.
- **Add Non Cash Salary Feature:** Provides a modal to transfer amounts from a non‑payable component to a payable one.
  - **Source Component:** Select the component marked as Not Payable.
  - **Target Component:** Choose the payable component to receive the transferred amount.
  - **Date Range:** Specify start and end months for the transfer period.
  - **Select Employee:** Search and select the employee.
  - **Important Notice:** Importing or adding data overwrites existing data for the selected employee and target component within the date range.

# Deduction Variable - Testing & Comparison Report

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
- **Missing/Issue:** Implement routes `store`, `copyPrevious`, `exportExcel`, and `uploadExcel` in `DeductionVariableController`. Use `Maatwebsite\Excel` for handling Excel files and ensure proper validation of deduction components and amounts.

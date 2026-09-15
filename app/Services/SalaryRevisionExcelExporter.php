<?php

namespace App\Services;

use ZipArchive;

class SalaryRevisionExcelExporter
{
    /**
     * Generate native .xlsx file content matching the user's template.
     */
    public static function generate($data)
    {
        $companyName = htmlspecialchars($data['companyName'] ?? 'SOMYA AUTOCAR PRIVATE LIMITED', ENT_XML1);
        $companyAddress = htmlspecialchars($data['companyAddress'] ?? '', ENT_XML1);
        $cityState = htmlspecialchars($data['cityState'] ?? '', ENT_XML1);
        $fullName = htmlspecialchars($data['fullName'] ?? '', ENT_XML1);
        $empCode = htmlspecialchars((string)($data['empCode'] ?? ''), ENT_XML1);
        $doj = htmlspecialchars($data['doj'] ?? '', ENT_XML1);
        $dept = htmlspecialchars($data['dept'] ?? '', ENT_XML1);
        $desig = htmlspecialchars($data['desig'] ?? '', ENT_XML1);
        $revisions = $data['revisions'] ?? [];

        // Temporary file in storage directory
        $tempDir = storage_path('framework/cache');
        if (!is_dir($tempDir)) {
            @mkdir($tempDir, 0777, true);
        }
        $tempFile = $tempDir . '/xlsx_' . uniqid() . '.zip';
        $zip = new ZipArchive();
        $zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // 1. [Content_Types].xml
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
  <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
  <Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>
</Types>';
        $zip->addFromString('[Content_Types].xml', $contentTypes);

        // 2. _rels/.rels
        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
</Relationships>';
        $zip->addFromString('_rels/.rels', $rels);

        // 3. xl/_rels/workbook.xml.rels
        $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
</Relationships>';
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);

        // 4. xl/workbook.xml
        $workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <sheets>
    <sheet name="Sheet1" sheetId="1" r:id="rId1"/>
  </sheets>
</workbook>';
        $zip->addFromString('xl/workbook.xml', $workbook);

        // 5. xl/styles.xml
        // Style 0: Normal
        // Style 1: Company Header (Bold 13, Center, Thin Border)
        // Style 2: Company Sub (Regular 10, Center, Thin Border)
        // Style 3: Section Title (Bold 11, Center, Thin Border)
        // Style 4: Meta Label (Bold 11, Left, Thin Border)
        // Style 5: Meta Value (Regular 11, Left, Thin Border)
        // Style 6: Empty Cell with Border
        // Style 7: Table Header (Bold 11 White, Blue Fill #1F4E78, Left, Border)
        // Style 8: Table Header Right (Bold 11 White, Blue Fill #1F4E78, Right, Border)
        // Style 9: Data Date (Regular 11, Left, Border)
        // Style 10: Data Amount (Regular 11, Right, NumberFormat #,##0.00, Border)
        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <numFmts count="1">
    <numFmt numFmtId="164" formatCode="#,##0.00"/>
  </numFmts>
  <fonts count="4">
    <font><name val="Calibri"/><sz val="11"/></font>
    <font><b/><name val="Calibri"/><sz val="13"/></font>
    <font><b/><name val="Calibri"/><sz val="11"/></font>
    <font><b/><color rgb="FFFFFFFF"/><name val="Calibri"/><sz val="11"/></font>
  </fonts>
  <fills count="3">
    <fill><patternFill patternType="none"/></fill>
    <fill><patternFill patternType="gray125"/></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FF1F4E78"/></patternFill></fill>
  </fills>
  <borders count="2">
    <border><left/><right/><top/><bottom/></border>
    <border>
      <left style="thin"><color rgb="FFB0B0B0"/></left>
      <right style="thin"><color rgb="FFB0B0B0"/></right>
      <top style="thin"><color rgb="FFB0B0B0"/></top>
      <bottom style="thin"><color rgb="FFB0B0B0"/></bottom>
    </border>
  </borders>
  <cellStyleXfs count="1">
    <xf numFmtId="0" fontId="0" fillId="0" borderId="0"/>
  </cellStyleXfs>
  <cellXfs count="11">
    <xf numFmtId="0" fontId="0" fillId="0" borderId="1" applyBorder="1"/>
    <xf numFmtId="0" fontId="1" fillId="0" borderId="1" applyFont="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>
    <xf numFmtId="0" fontId="0" fillId="0" borderId="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>
    <xf numFmtId="0" fontId="2" fillId="0" borderId="1" applyFont="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>
    <xf numFmtId="0" fontId="2" fillId="0" borderId="1" applyFont="1" applyBorder="1" applyAlignment="1"><alignment horizontal="left" vertical="center"/></xf>
    <xf numFmtId="0" fontId="0" fillId="0" borderId="1" applyBorder="1" applyAlignment="1"><alignment horizontal="left" vertical="center"/></xf>
    <xf numFmtId="0" fontId="0" fillId="0" borderId="1" applyBorder="1"/>
    <xf numFmtId="0" fontId="3" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="left" vertical="center"/></xf>
    <xf numFmtId="0" fontId="3" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="right" vertical="center"/></xf>
    <xf numFmtId="0" fontId="0" fillId="0" borderId="1" applyBorder="1" applyAlignment="1"><alignment horizontal="left" vertical="center"/></xf>
    <xf numFmtId="164" fontId="0" fillId="0" borderId="1" applyNumberFormat="1" applyBorder="1" applyAlignment="1"><alignment horizontal="right" vertical="center"/></xf>
  </cellXfs>
</styleSheet>';
        $zip->addFromString('xl/styles.xml', $styles);

        // 6. xl/worksheets/sheet1.xml
        $sheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <cols>
    <col min="1" max="1" width="18" customWidth="1"/>
    <col min="2" max="2" width="18" customWidth="1"/>
    <col min="3" max="3" width="28" customWidth="1"/>
    <col min="4" max="4" width="28" customWidth="1"/>
    <col min="5" max="5" width="12" customWidth="1"/>
  </cols>
  <sheetData>';

        // Row 1
        $sheet .= '<row r="1" ht="24" customHeight="1">
          <c r="A1" s="1" t="inlineStr"><is><t>' . $companyName . '</t></is></c>
          <c r="B1" s="1"/>
          <c r="C1" s="1"/>
          <c r="D1" s="1"/>
          <c r="E1" s="6"/>
        </row>';

        // Row 2
        $sheet .= '<row r="2" ht="18" customHeight="1">
          <c r="A2" s="2" t="inlineStr"><is><t>' . $companyAddress . '</t></is></c>
          <c r="B2" s="2"/>
          <c r="C2" s="2"/>
          <c r="D2" s="2"/>
          <c r="E2" s="6"/>
        </row>';

        // Row 3
        $sheet .= '<row r="3" ht="18" customHeight="1">
          <c r="A3" s="2" t="inlineStr"><is><t>' . $cityState . '</t></is></c>
          <c r="B3" s="2"/>
          <c r="C3" s="2"/>
          <c r="D3" s="2"/>
          <c r="E3" s="6"/>
        </row>';

        // Row 4
        $sheet .= '<row r="4" ht="14" customHeight="1">
          <c r="A4" s="6"/><c r="B4" s="6"/><c r="C4" s="6"/><c r="D4" s="6"/><c r="E4" s="6"/>
        </row>';

        // Row 5
        $sheet .= '<row r="5" ht="20" customHeight="1">
          <c r="A5" s="3" t="inlineStr"><is><t>List of Salary Revisions</t></is></c>
          <c r="B5" s="3"/>
          <c r="C5" s="3"/>
          <c r="D5" s="3"/>
          <c r="E5" s="6"/>
        </row>';

        // Row 6
        $sheet .= '<row r="6" ht="14" customHeight="1">
          <c r="A6" s="6"/><c r="B6" s="6"/><c r="C6" s="6"/><c r="D6" s="6"/><c r="E6" s="6"/>
        </row>';

        // Row 7 (Employee Name)
        $sheet .= '<row r="7" ht="18" customHeight="1">
          <c r="A7" s="4" t="inlineStr"><is><t>Employee Name</t></is></c>
          <c r="B7" s="5" t="inlineStr"><is><t>' . $fullName . '</t></is></c>
          <c r="C7" s="6"/><c r="D7" s="6"/><c r="E7" s="6"/>
        </row>';

        // Row 8 (Employee Code)
        $sheet .= '<row r="8" ht="18" customHeight="1">
          <c r="A8" s="4" t="inlineStr"><is><t>Employee Code</t></is></c>
          <c r="B8" s="5" t="inlineStr"><is><t>' . $empCode . '</t></is></c>
          <c r="C8" s="6"/><c r="D8" s="6"/><c r="E8" s="6"/>
        </row>';

        // Row 9 (Date of Joining)
        $sheet .= '<row r="9" ht="18" customHeight="1">
          <c r="A9" s="4" t="inlineStr"><is><t>Date of Joining</t></is></c>
          <c r="B9" s="5" t="inlineStr"><is><t>' . $doj . '</t></is></c>
          <c r="C9" s="6"/><c r="D9" s="6"/><c r="E9" s="6"/>
        </row>';

        // Row 10 (Department)
        $sheet .= '<row r="10" ht="18" customHeight="1">
          <c r="A10" s="4" t="inlineStr"><is><t>Department</t></is></c>
          <c r="B10" s="5" t="inlineStr"><is><t>' . $dept . '</t></is></c>
          <c r="C10" s="6"/><c r="D10" s="6"/><c r="E10" s="6"/>
        </row>';

        // Row 11 (Designation)
        $sheet .= '<row r="11" ht="18" customHeight="1">
          <c r="A11" s="4" t="inlineStr"><is><t>Designation</t></is></c>
          <c r="B11" s="5" t="inlineStr"><is><t>' . $desig . '</t></is></c>
          <c r="C11" s="6"/><c r="D11" s="6"/><c r="E11" s="6"/>
        </row>';

        // Row 12
        $sheet .= '<row r="12" ht="14" customHeight="1">
          <c r="A12" s="6"/><c r="B12" s="6"/><c r="C12" s="6"/><c r="D12" s="6"/><c r="E12" s="6"/>
        </row>';

        // Row 13 (Headers)
        $sheet .= '<row r="13" ht="22" customHeight="1">
          <c r="A13" s="7" t="inlineStr"><is><t>Effective Date</t></is></c>
          <c r="B13" s="8" t="inlineStr"><is><t>Net Salary</t></is></c>
          <c r="C13" s="8" t="inlineStr"><is><t>Cost to Company (Monthly)</t></is></c>
          <c r="D13" s="8" t="inlineStr"><is><t>Cost to Company (Yearly)</t></is></c>
          <c r="E13" s="6"/>
        </row>';

        // Data Rows
        $currentRow = 14;
        if (!empty($revisions)) {
            foreach ($revisions as $rev) {
                $effectiveDate = strtoupper(\Carbon\Carbon::parse($rev->effective_from)->format('d-M-Y'));
                $netSalary = (float)$rev->net_salary;
                $ctcMonthly = (float)$rev->ctc;
                $ctcYearly = (float)($rev->ctc * 12);

                $sheet .= '<row r="' . $currentRow . '" ht="20" customHeight="1">
                  <c r="A' . $currentRow . '" s="9" t="inlineStr"><is><t>' . $effectiveDate . '</t></is></c>
                  <c r="B' . $currentRow . '" s="10"><v>' . $netSalary . '</v></c>
                  <c r="C' . $currentRow . '" s="10"><v>' . $ctcMonthly . '</v></c>
                  <c r="D' . $currentRow . '" s="10"><v>' . $ctcYearly . '</v></c>
                  <c r="E' . $currentRow . '" s="6"/>
                </row>';
                $currentRow++;
            }
        }

        $sheet .= '</sheetData>
  <mergeCells count="4">
    <mergeCell ref="A1:D1"/>
    <mergeCell ref="A2:D2"/>
    <mergeCell ref="A3:D3"/>
    <mergeCell ref="A5:D5"/>
  </mergeCells>
</worksheet>';
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheet);
        $zip->close();

        $content = file_get_contents($tempFile);
        unlink($tempFile);

        return $content;
    }
}

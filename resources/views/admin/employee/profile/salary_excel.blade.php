{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
{!! '<' . '?mso-application progid="Excel.Sheet"?' . '>' !!}
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Author>SomyaHRMS</Author>
  <Created>{{ date('Y-m-d\TH:i:s\Z') }}</Created>
  <Company>{{ $companyName }}</Company>
 </DocumentProperties>
 <Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Center"/>
   <Borders/>
   <Font ss:FontName="Calibri" ss:Size="11" ss:Color="#000000"/>
   <Interior/>
   <NumberFormat/>
   <Protection/>
  </Style>
  <Style ss:ID="CompanyHeader">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Font ss:FontName="Calibri" ss:Size="13" ss:Bold="1"/>
  </Style>
  <Style ss:ID="CompanySub">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Font ss:FontName="Calibri" ss:Size="10"/>
  </Style>
  <Style ss:ID="SectionTitle">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Font ss:FontName="Calibri" ss:Size="11" ss:Bold="1"/>
  </Style>
  <Style ss:ID="MetaLabel">
   <Alignment ss:Horizontal="Left" ss:Vertical="Center"/>
   <Font ss:FontName="Calibri" ss:Size="11" ss:Bold="1"/>
  </Style>
  <Style ss:ID="MetaValue">
   <Alignment ss:Horizontal="Left" ss:Vertical="Center"/>
   <Font ss:FontName="Calibri" ss:Size="11"/>
  </Style>
  <Style ss:ID="TableHeader">
   <Alignment ss:Horizontal="Left" ss:Vertical="Center"/>
   <Font ss:FontName="Calibri" ss:Size="11" ss:Bold="1" ss:Color="#FFFFFF"/>
   <Interior ss:Color="#2C5282" ss:Pattern="Solid"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#A0AEC0"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#A0AEC0"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#A0AEC0"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#A0AEC0"/>
   </Borders>
  </Style>
  <Style ss:ID="TableHeaderRight">
   <Alignment ss:Horizontal="Right" ss:Vertical="Center"/>
   <Font ss:FontName="Calibri" ss:Size="11" ss:Bold="1" ss:Color="#FFFFFF"/>
   <Interior ss:Color="#2C5282" ss:Pattern="Solid"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#A0AEC0"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#A0AEC0"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#A0AEC0"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#A0AEC0"/>
   </Borders>
  </Style>
  <Style ss:ID="DataDate">
   <Alignment ss:Horizontal="Left" ss:Vertical="Center"/>
   <Font ss:FontName="Calibri" ss:Size="11"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#DCDCDC"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#DCDCDC"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#DCDCDC"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#DCDCDC"/>
   </Borders>
  </Style>
  <Style ss:ID="DataAmount">
   <Alignment ss:Horizontal="Right" ss:Vertical="Center"/>
   <Font ss:FontName="Calibri" ss:Size="11"/>
   <NumberFormat ss:Format="#,##0.00"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#DCDCDC"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#DCDCDC"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#DCDCDC"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#DCDCDC"/>
   </Borders>
  </Style>
 </Styles>
 <Worksheet ss:Name="Salary Revisions">
  <Table ss:DefaultRowHeight="20">
   <Column ss:Width="140"/>
   <Column ss:Width="140"/>
   <Column ss:Width="190"/>
   <Column ss:Width="190"/>
   
   <Row ss:Height="24">
    <Cell ss:MergeAcross="3" ss:StyleID="CompanyHeader"><Data ss:Type="String">{{ $companyName }}</Data></Cell>
   </Row>
   <Row ss:Height="18">
    <Cell ss:MergeAcross="3" ss:StyleID="CompanySub"><Data ss:Type="String">{{ $companyAddress }}</Data></Cell>
   </Row>
   <Row ss:Height="18">
    <Cell ss:MergeAcross="3" ss:StyleID="CompanySub"><Data ss:Type="String">{{ $cityState }}</Data></Cell>
   </Row>
   <Row ss:Height="12"></Row>
   <Row ss:Height="20">
    <Cell ss:MergeAcross="3" ss:StyleID="SectionTitle"><Data ss:Type="String">List of Salary Revisions</Data></Cell>
   </Row>
   <Row ss:Height="12"></Row>
   <Row ss:Height="18">
    <Cell ss:StyleID="MetaLabel"><Data ss:Type="String">Employee Name</Data></Cell>
    <Cell ss:StyleID="MetaValue"><Data ss:Type="String">{{ $fullName }}</Data></Cell>
    <Cell ss:StyleID="Default"/>
    <Cell ss:StyleID="Default"/>
   </Row>
   <Row ss:Height="18">
    <Cell ss:StyleID="MetaLabel"><Data ss:Type="String">Employee Code</Data></Cell>
    <Cell ss:StyleID="MetaValue"><Data ss:Type="String">{{ $empCode }}</Data></Cell>
    <Cell ss:StyleID="Default"/>
    <Cell ss:StyleID="Default"/>
   </Row>
   <Row ss:Height="18">
    <Cell ss:StyleID="MetaLabel"><Data ss:Type="String">Date of Joining</Data></Cell>
    <Cell ss:StyleID="MetaValue"><Data ss:Type="String">{{ $doj }}</Data></Cell>
    <Cell ss:StyleID="Default"/>
    <Cell ss:StyleID="Default"/>
   </Row>
   <Row ss:Height="18">
    <Cell ss:StyleID="MetaLabel"><Data ss:Type="String">Department</Data></Cell>
    <Cell ss:StyleID="MetaValue"><Data ss:Type="String">{{ $dept }}</Data></Cell>
    <Cell ss:StyleID="Default"/>
    <Cell ss:StyleID="Default"/>
   </Row>
   <Row ss:Height="18">
    <Cell ss:StyleID="MetaLabel"><Data ss:Type="String">Designation</Data></Cell>
    <Cell ss:StyleID="MetaValue"><Data ss:Type="String">{{ $desig }}</Data></Cell>
    <Cell ss:StyleID="Default"/>
    <Cell ss:StyleID="Default"/>
   </Row>
   <Row ss:Height="14"></Row>
   <Row ss:Height="22">
    <Cell ss:StyleID="TableHeader"><Data ss:Type="String">Effective Date</Data></Cell>
    <Cell ss:StyleID="TableHeaderRight"><Data ss:Type="String">Net Salary</Data></Cell>
    <Cell ss:StyleID="TableHeaderRight"><Data ss:Type="String">Cost to Company (Monthly)</Data></Cell>
    <Cell ss:StyleID="TableHeaderRight"><Data ss:Type="String">Cost to Company (Yearly)</Data></Cell>
   </Row>
   @if($revisions && $revisions->count() > 0)
     @foreach($revisions as $rev)
     <Row ss:Height="20">
      <Cell ss:StyleID="DataDate"><Data ss:Type="String">{{ strtoupper(\Carbon\Carbon::parse($rev->effective_from)->format('d-M-Y')) }}</Data></Cell>
      <Cell ss:StyleID="DataAmount"><Data ss:Type="Number">{{ (float)$rev->net_salary }}</Data></Cell>
      <Cell ss:StyleID="DataAmount"><Data ss:Type="Number">{{ (float)$rev->ctc }}</Data></Cell>
      <Cell ss:StyleID="DataAmount"><Data ss:Type="Number">{{ (float)$rev->ctc * 12 }}</Data></Cell>
     </Row>
     @endforeach
   @endif
  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <Selected/>
   <Panes>
    <Pane>
     <Number>3</Number>
     <ActiveRow>0</ActiveRow>
     <ActiveCol>0</ActiveCol>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>

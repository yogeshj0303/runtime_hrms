Set WinScriptHost = CreateObject("WScript.Shell")
WinScriptHost.Run Chr(34) & "C:\xampp\php\php.exe" & Chr(34) & " C:\xampp\htdocs\SomyaHRMS\artisan schedule:run", 0
Set WinScriptHost = Nothing

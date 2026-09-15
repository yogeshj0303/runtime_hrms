Set WinScriptHost = CreateObject("WScript.Shell")
' Run the queue worker silently to instantly send emails
WinScriptHost.Run Chr(34) & "C:\xampp\php\php.exe" & Chr(34) & " C:\xampp\htdocs\SomyaHRMS\artisan queue:work", 0, False
' Run the schedule worker silently to check for alerts
WinScriptHost.Run Chr(34) & "C:\xampp\php\php.exe" & Chr(34) & " C:\xampp\htdocs\SomyaHRMS\artisan schedule:work", 0, False
Set WinScriptHost = Nothing

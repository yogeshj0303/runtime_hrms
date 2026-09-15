<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">

    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        
        {!! $letter->generated_html !!}

        <hr style="border: none; border-top: 1px solid #eeeeee; margin-top: 40px; margin-bottom: 20px;">
        <p style="font-size: 12px; color: #777777; text-align: center;">
            This is an automated letter from your HR department. Please do not reply directly to this email.
        </p>
    </div>

</body>
</html>

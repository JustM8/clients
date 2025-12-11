<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Нагадування по вашому проєкту</title>
</head>
<body style="font-family: 'Inter', Arial, sans-serif; background-color:#1a1a1a; padding: 30px; margin: 0;">
<table align="center" cellpadding="0" cellspacing="0" width="600"
       style="background:#2a2a2a; border-radius:16px; overflow:hidden; box-shadow:0 10px 40px rgba(0,0,0,0.5); border: 1px solid rgba(255, 72, 0, 0.2);">

    <tr>
        <td style="padding:30px; background: linear-gradient(135deg, #ff4800 0%, #ff6b00 100%); text-align: center;">
            <svg width="104" height="26" viewBox="0 0 104 26" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 10px;">
                <path d="M4.00675 6.89969C4.00675 7.55465 4.8377 7.81075 7.87754 7.81075C11.2243 7.81075 11.646 7.59243 11.646 7.18098C11.646 6.74434 11.4164 6.52602 7.39317 6.39797C1.96488 6.21744 0.520119 5.31897 0.520119 3.3289C0.520119 1.20868 2.43673 0.104492 7.62283 0.104492C13.1409 0.104492 14.8779 0.797236 14.8779 3.03291C14.8779 3.22604 14.8779 3.41917 14.8654 3.63749C14.4834 3.6123 13.8048 3.5997 13.1534 3.5997C12.477 3.5997 11.7984 3.6123 11.4164 3.63749V3.39398C11.4164 2.73902 10.9571 2.5207 7.57064 2.5207C4.45355 2.5207 3.99423 2.71383 3.99423 3.09799C3.99423 3.48215 4.23641 3.65009 8.51641 3.8684C13.5877 4.12451 15.1452 4.86973 15.1452 7.02774C15.1452 9.27601 13.3706 10.355 7.91512 10.355C2.34486 10.355 0.442871 9.41666 0.442871 7.46438C0.442871 7.23346 0.455398 6.98995 0.495066 6.73175C0.941856 6.78213 1.61831 6.82202 2.25717 6.82202C2.90857 6.82202 3.52238 6.80942 4.00675 6.78423V6.89969Z" fill="white"></path>
            </svg>
            <h2 style="margin:10px 0 0 0; font-weight:700; color:white; font-size: 20px;">⏳ Нагадування про проєкт</h2>
        </td>
    </tr>

    <tr>
        <td style="padding:30px; color: rgba(255, 255, 255, 0.9);">

            <p style="margin:0 0 15px; color: rgba(255, 255, 255, 0.7);"><strong style="color: #ff4800;">Клієнт:</strong> {{$project->client?->name ?? '—'}}</p>
            <p style="margin:0 0 15px; color: rgba(255, 255, 255, 0.7);"><strong style="color: #ff4800;">Назва проєкту:</strong> {{$project->name}}</p>

            <p style="margin:0 0 15px; color: rgba(255, 255, 255, 0.7);"><strong style="color: #ff4800;">Поточна стадія:</strong>
                <span style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color:#fff; padding:5px 12px; border-radius:8px; display: inline-block; margin-top: 5px;">
                    {{$project->status?->name ?? '—'}}
                </span>
            </p>

            <p style="margin:0 0 20px; color: rgba(255, 255, 255, 0.5); font-size: 13px;">Дата: {{ now()->format('d.m.Y H:i') }}</p>

            <div style="background: rgba(255, 72, 0, 0.1); border-left: 3px solid #ff4800; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <p style="font-size:15px; line-height:1.6; color: rgba(255, 255, 255, 0.9); margin: 0;">
                    Нагадуємо, що на даному етапі нам потрібна ваша відповідь або затвердження,
                    щоб продовжити роботу над проєктом.
                </p>
            </div>

            <p style="font-size:15px; line-height:1.6; color: rgba(255, 255, 255, 0.8); margin: 20px 0;">
                Будь ласка, перейдіть у свій кабінет, щоб написати менеджеру або підтвердити інформацію:
            </p>

            <div style="text-align: center; margin: 25px 0;">
                <a href="https://clients.smarto.agency/projects/{{$project->id}}"
                   style="display: inline-block; background: linear-gradient(135deg, #ff4800 0%, #ff6b00 100%); color: white; text-decoration: none; padding: 12px 30px; border-radius: 12px; font-weight: 600;">
                    Відкрити проєкт →
                </a>
            </div>

            <p style="margin-top:25px; font-size:13px; color: rgba(255, 255, 255, 0.5);">
                Якщо у вас є питання — можете просто натиснути «Відповісти» на цей лист.
            </p>
        </td>
    </tr>

    <tr>
        <td style="padding:20px 30px; background:rgba(13, 13, 13, 0.6); font-size:12px; color: rgba(255, 255, 255, 0.5); text-align:center; border-top: 1px solid rgba(255, 72, 0, 0.2);">
            Відправлено автоматично з системи <strong style="color: #ff4800;">Smarto Agency</strong>
        </td>
    </tr>

</table>
</body>
</html>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Нагадування по вашому проєкту</title>
</head>
<body style="font-family: Arial, sans-serif; background-color:#f7f7f7; padding: 30px;">
<table align="center" cellpadding="0" cellspacing="0" width="600"
       style="background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.1);">

    <tr>
        <td style="padding:20px 30px; background:#fff8e5; border-bottom:1px solid #eee;">
            <h2 style="margin:0; font-weight:600; color:#333;">⏳ Нагадування: проєкт очікує на вашу відповідь</h2>
        </td>
    </tr>

    <tr>
        <td style="padding:30px;">

            <p><strong>Клієнт:</strong> {{ $project->client?->name ?? '—' }}</p>
            <p><strong>Назва проєкту:</strong> {{ $project->name }}</p>

            <p><strong>Дата нагадування:</strong> {{ now()->format('d.m.Y H:i') }}</p>

            <p style="font-size:15px; line-height:1.5; color:#444;">
                Нагадуємо, що на даному етапі нам потрібна ваша відповідь або затвердження,
                щоб продовжити роботу над проєктом.
            </p>

            <p style="font-size:15px; line-height:1.5; color:#444;">
                Будь ласка, <a href="https://clients.smarto.agency/projects/{{ $project->id }}"
                               style="color:#007bff; text-decoration:none;">
                    перейдіть у свій кабінет
                </a>, щоб написати менеджеру або підтвердити інформацію.
            </p>

            <p style="margin-top:25px; font-size:13px; color:#777;">
                Якщо у вас є питання — можете просто натиснути «Відповісти» на цей лист.
            </p>
        </td>
    </tr>

    <tr>
        <td style="padding:15px 30px; background:#f9f9f9; font-size:12px; color:#777; text-align:center;">
            Відправлено автоматично з системи <strong>Smarto Agency</strong>
        </td>
    </tr>
</table>
</body>
</html>

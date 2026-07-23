import { getRangeLast8Hours } from "../../../Utils/date.js";

document.addEventListener("DOMContentLoaded", async () => {
    const range_date = getRangeLast8Hours();
    const notifications = await getNotificationsHistory(range_date);
    const html_notifications = Notification(notifications);

    document.getElementById("notification-list").innerHTML = html_notifications;
    // $("#notif-count").text(notifications.length);

    setInterval(async () => {

        const range_date = getRangeLast8Hours();

        const new_notifications = await getNotificationsHistory(range_date);
        const new_html_notifications = Notification(new_notifications);

        document.getElementById("notification-list").innerHTML = new_html_notifications;
        $("#notif-count").text(new_notifications.length);
    }, 60000);

});

const getNotificationsHistory = async (range_date) => {
    const url = "http://ws4cjdg.com/JDigitalReportsV2/src/api/routes/utils/getQuery.php";

    try {
        const response = await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                query: `SELECT * FROM notifications WHERE Date BETWEEN '${range_date.from}' AND '${range_date.to}' ORDER BY Date DESC`
                // query: "SELECT n.* FROM notifications n INNER JOIN ( SELECT notification_description, MAX(Date) AS max_date FROM notifications WHERE Date BETWEEN '2026-07-16 07:00:00' AND '2026-07-16 12:00:00' GROUP BY notification_description ) latest ON n.notification_description = latest.notification_description AND n.Date = latest.max_date"
            })
        });

        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }

        const data = await response.json();

        if (data.status == 'ok') {
            return data.mensaje;
        }

    } catch (error) {
        console.error("Error al obtener el historial de notificaciones:", error);
    }
}

const Notification = (notifications) => {
    const list = notifications.map(n => {
        const parse_notification = parseNotification(n.notification_description);
        return `
            <li class="list-group-item notif-item notif-info d-flex gap-2 py-2 px-3">
                
                <div class="notif-icon bg-primary-subtle text-primary">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>

                <div class="flex-grow-1 d-flex justify-content-between align-items-stretch">

                    <div class="min-width-0">
                        <p class="mb-0 fw-semibold small text-truncate">
                            ${parse_notification.unit} - Variacion de temperatura
                        </p>

                        <p class="mb-0 text-secondary" style="font-size:.75rem;">
                            Temperatura: ${parse_notification.temperature}°C
                        </p>

                        <span class="notif-time">${n.date}</span>
                    </div>

                    <!--<button class="btn btn-sm btn-primary ms-3" onclick="">
                        Atender
                    </button>-->
                </div>
            </li>`;
    });

    return list.join("");
}

function parseNotification(text) {

    const unitMatch = text.match(/^(.+?):/);
    const tempMatch = text.match(/sensor TEMPERATURA activado con el valor\s+([-+]?\d+\.?\d*)\s*°C/i);

    return {
        unit: unitMatch ? unitMatch[1].trim() : null,
        temperature: tempMatch ? parseFloat(tempMatch[1]) : null
    };
}
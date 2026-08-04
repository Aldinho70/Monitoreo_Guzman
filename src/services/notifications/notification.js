import { getRangeLast8Hours } from "../../../Utils/date.js";
import NotificationsService from "../notifcations_service.js";

const notificationsService = new NotificationsService();

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
                query: `SELECT *
                        FROM view_notifications 
                        WHERE 
                            notification_follow_id IS NULL
                            AND
                            notification_date BETWEEN '${range_date.from}' AND '${range_date.to}' 
                        ORDER BY notification_date DESC`
                // query: `SELECT * FROM notifications WHERE Date BETWEEN '${range_date.from}' AND '${range_date.to}' ORDER BY Date DESC`
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
    console.log( notifications );
    
    const maxVisibleNotifications = 15;
    const visibleNotifications = notifications.slice(0, maxVisibleNotifications);
    const list = visibleNotifications.map(n => {
        const parse_notification = parseNotification(n.notification_description);
        const temperatureText = parse_notification.temperature !== null ? `${parse_notification.temperature}°C` : "--";

        return `
            <li class="list-group-item notif-item notif-info d-flex gap-2 align-items-start">
                <div class="notif-icon bg-primary-subtle text-primary">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>

                <div class="notif-content flex-grow-1 min-width-0">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <span class="notif-title text-truncate">
                            ${parse_notification.unit || "Notificación"} - Variación temperatura
                        </span>
                        <span class="notif-time">${n.notification_date}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-1 gap-2">
                        <span class="notif-subtitle text-secondary">Temp ${temperatureText}</span>
                        <button class="btn btn-sm btn-danger btn-notif-attend" onClick="sendRequest(${n.notification_id})" type="button">Atender</button>
                    </div>
                </div>
            </li>`;
    });

    if (notifications.length > maxVisibleNotifications) {
        list.push(`
            <li class="list-group-item notif-note">
                Mostrando ${maxVisibleNotifications} de ${notifications.length} notificaciones recientes.
            </li>`);
    }

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

const sendRequest = async (notification_id) => {
    
    const payload = {
        notification_id,
        monitorist: 'monitoreo1',
        comment: 'Atendida via dashboard',
        resolution: 'otra'
    };

    try {
        const response = await notificationsService.attendNotification(payload);
        if (response.status === 'ok') {
            alert( 'Notificación atendida exitosamente' );

            setTimeout(() => {
                location.reload();
            }, 3000);

        } else {
            console.log( response );
        }
    } catch (error) {
        console.error('Error al atender la notificación:', error);
    }
};
window.sendRequest = sendRequest;
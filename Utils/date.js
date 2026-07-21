export function getRangeLast8Hours() {

    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        const seconds = String(date.getSeconds()).padStart(2, '0');

        return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    };

    const now = new Date();
    const eightHoursAgo = new Date(now.getTime() - (8 * 60 * 60 * 1000));

    return {
        from: formatDate(eightHoursAgo),
        to: formatDate(now)
    };
}
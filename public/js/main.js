// Инициализируем Flatpickr для всех полей с классом .date-picker
document.querySelectorAll('.date-picker').forEach(input => {
    flatpickr(input, {
        enableTime: true,        // Включить выбор времени
        dateFormat: "d.m.Y", // Формат даты (день.месяц.год часы:минуты)
        time_24hr: true,         // 24-часовой формат времени
        locale: "ru",            // Русский язык
        static: false,           // Календарь будет закрываться после выбора
        allowInput: false,       // Запретить ручной ввод (только выбор из календаря)
        minuteIncrement: 15,     // Шаг минут (опционально)
        defaultDate: input.value || "today",  // Текущая дата, если нет значения
    });
});
#!/bin/bash

echo "🗄️  10000-hour DATABASE"
echo "=========================="
echo ""

echo "👥 USERS:"
sqlite3 database/database.sqlite -header -column "SELECT username, email, role FROM users;"
echo ""

echo "📚 HABITS:"
sqlite3 database/database.sqlite -header -column "SELECT name, description, daily_count FROM habits;"
echo ""

echo "📊 HABIT LOGS (Recent 10):"
sqlite3 database/database.sqlite -header -column "
SELECT h.name as habit, hl.date, u.username 
FROM habits_logs hl 
JOIN habits h ON hl.habit_id = h.id 
JOIN users u ON hl.user_id = u.id 
ORDER BY hl.created_at DESC 
LIMIT 10;"
echo ""

echo "📈 HABIT STATISTICS:"
sqlite3 database/database.sqlite -header -column "
SELECT h.name, COUNT(hl.id) as total_logs, h.daily_count
FROM habits h 
LEFT JOIN habits_logs hl ON h.id = hl.habit_id 
GROUP BY h.id, h.name;"
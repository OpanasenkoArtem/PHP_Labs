# Helpdesk API

Формат відповіді: `{"success": true, "data": ...}` або `{"success": false, "error": "..."}`

| Запит | Успіх | Помилки |
|---|---|---|
| `GET api.php?resource=tickets` | 200, масив тікетів | — |
| `GET api.php?resource=tickets&id=N` | 200, один тікет | 400 (id некоректний), 404 |
| `POST api.php?resource=tickets` (`subject`, `priority`) | 201, створений тікет | 400 |
| `POST api.php?resource=tickets&action=close&id=N` | 200, тікет зі `status: "closed"` | 400, 404 |
| інший метод для `tickets` | — | 405 (+ заголовок `Allow`) |
| невідомий `resource` | — | 404 |
| невідома `action` | — | 400 |

## Перевірка curl

```
curl -i "http://localhost/pr6/api.php?resource=tickets"
curl -i "http://localhost/pr6/api.php?resource=tickets&id=999"
curl -i -X POST "http://localhost/pr6/api.php?resource=tickets" -H "Content-Type: application/json" -d '{"subject":"Тест","priority":"high"}'
curl -i -X POST "http://localhost/pr6/api.php?resource=tickets" -H "Content-Type: application/json" -d '{"priority":"high"}'
curl -i -X POST "http://localhost/pr6/api.php?resource=tickets&action=close&id=1"
curl -i -X DELETE "http://localhost/pr6/api.php?resource=tickets"
curl -i "http://localhost/pr6/api.php?resource=users"
```

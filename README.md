# Платежный модуль для Opencart 1.5  (тестрировалось на версии 1.5.6)

Модуль позволяет принимать платежи банковской картой через Модульбанк.

Данная версия написана путем адаптации модуля под Opencart 2.0, при этом сделан определённый рефакторинг и добавлен вывод опций товара вместе с названием в чек.

### Установка

1. Скопируйте файлы из upload/admin в папку на вашем сервере, где установлен opencart, например public_html/admin.
Скопируйте файлы из upload/catalog в папку на вашем сервере, где установлен opencart, например public_html/catalog.
2. Зайдите в панель администратора, Модули -> Платежи, найдите в списке Модульбанк, нажмите Установить.
После этого зайдите в настройки модуля платежей Модульбанк.

5. На странице настроек укажите название способа оплаты, которе будет отображаться покупателю на странице оформления заказа, идентификатор и секретный ключ вашего магазина, которые можно найти в личном кабинете Модульбанка. При необходимости включите или отключите тестовый режим.
Для правильной отправки чеков требуется указать систему налогообложения, предмет расчета, метод платежа и ставку НДС по-умолчанию.
6. Нажмите на кнопку "Сохранить".

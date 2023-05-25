# Senior backend teszt project

## Rendszer követelmények
- PHP7.2 (Laravel 8)
- PHP Composer
- Postgresql 14

## Adatbázis feltöltés
A táblázat létrehozásához és feltöltéséhez futtasd az alábbi parancsot:
```
php artisan migrate --seed
```
## API végpontok

Cégek lekérdezése (GET):
```
/api/companies?ids[]=1&ids[]=2
```
Cég hozzáadása (POST):
```
/api/companies/new
```
Cég módosítása (PATCH):
```
/api/companies/{companyId}
```
## SQL lekérdezések
Az SQL feladatok forrásai a **database** mappában találhatóak.

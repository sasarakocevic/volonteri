# Volonteri

## Opis projekta

Svaka od navedenih (projektom tazenih) funkcionalnosti mora biti posebno Git push-ovana na grani koja je dodijeljena za projekat u suprotnom zavrsni rad se nece bodovati. (Neprihvatiljvo je da sve funkcionalnosti budu u jednom git push-u)

Aplikacija ima 2 segmenta:

1. Darovanje stvari

     Darovanje je u vidu oglasa

    Darovanje moze sadrzati slike, lokaciju, tekst i opis.

    Donator koji nije volonter nema svoj nalog donaciju objavljuje tako sto postavi svoj mejl

    Donatorov mejl je uvjek sakriven

    Donator moze izmijeniti svoj oglas jedino ako stavi isti mejl koji je koristio za dodavanje oglasa

    Donator je u obavezi da izmijeni status donacije

2. Volonterske akcije

    Postavljanje volonterskih akcija rade administratori

    Akacija mora imati zakazan dan i vrijeme akcije, sa pozeljnim brojem volontera

    Svaka akcija ima opis sa ukljucenim editovanja kroz neki od postojecih editora WYSWYG

    Volonteri mogu imati svoj nalog

    Volonter moze biti i donator

    Administrator ima mogucnost ostaviti review akcije sa izmijenjenim statusom


## API

### User
- POST /api/register
- POST /api/login
- POST /api/logout

### Donacije

```json
{
    "id": 1,
    "naslov": "Donacija Prva",
    "opis": "Ovo je opis donacije.",
    "lokacija": "Lokacija",
    "slike": [
        {"id":1, "url": "http://..."},
        {"id":2, "url": "http://..."}
    ],
    "status": "aktivna" // aktivna | zavrsena
}
```

- GET /api/donacije
- POST /api/donacije (ako nije ulogovan email je neophodan ```{"email":"a@b.c"}``` )
- GET /api/donacije/{id}
- PUT /api/donacije/{id} ```{"email":"a@b.c"}```
- DELETE /api/donacije/{id} ```{"email":"a@b.c"}```


- POST /api/donacije/{id}/slike
- DELETE /api/donacije/{id}/slike/{id_slike}

### Akcije

```json
{
    "id": 1,
    "naslov": "Naslov akcija",
    "opis": "Ovo je duzi <h1>opis</h1> akcije.",
    "vrijeme": "2022-06-01T08:37:59.981Z",
    "brojVolontera": 5,
    "status": "aktivna", // aktivna | neaktivna | zavrsena
    "izvjestaj": "Izvjestaj koji postavlja administrator na zavrsenu akciju." // samo ako je status == zavrsena
}
```

- GET /api/akcije
- POST /api/akcije
- GET /api/akcije/{id}
- PUT /api/akcije/{id}
- DELETE /api/akcije/{id}


- POST /api/akcije/{id}/prijava
- POST /api/akcije/{id}/odjava

Progetto sviluppato in puro PHP, CSS e Javascript (jQuery) e SQL

Il progetto simula il funzionamento di un sistema per la gestione di un ristorante.

http://localhost/food-ordering/index.php

Ci sono 3 entità:  
- l'admin: l'amministratore che può aggiungere al sistema nuovi utenti (chef o camerieri) e che può inserire, eliminare o modificare il menu e avere un overview degli ordini ricevuti giornalmente, nell'ultima settimana, ultimo mese e di sempre.

- lo chef: prende in gestione gli ordini e può metterli in vari stati: "preparing" (in preparazione), "ready" (pronto e quindi gestibile dal cameriere), "waiting" (in coda ad altri ordini). Inoltre, lo chef, può cancellare definitivamente, tramite il tasto "Cancella" i vari ordini e può anche rimuoverli solamente dalla lista a frontend tramite il tasto "Rimuovi" (nel database non verranno camcellati in questo caso ma verranno settati a "finish", ovvero ordine concluso)

- il cameriere: può prendere le ordinazioni dalla sezione Ordini aggiungendo per ogni piatto ordinato le quantità, una volta che tutti gli ospiti della tavolata hanno ordinato allora può inviare l'ordine (che verrà preso in gestione dallo chef). Dalla dashboard può vedere gli ordini che gli chef hanno messo in stato "Ready" e che possono essere serviti ai clienti.

La Dashboard, per mantenere aggiornati i dipendenti del ristorante, viene aggiornata ogni 3 o 5 secondi a secondo che si tratti di uno chef o cameriere.

L'applicazione è a scopo dimostrativo quindi credenziali nella procedura di login non vengono criptate tramite alcun algoritmo (es. SHA1) e alla crreazione di un nuovo membro dello staff (che esso sia chef o cameriere) da parte dell'amministratore la password viene settata alla seguente stringa: "abc123".

Credenziali amministratore:
username:admin
password:admin

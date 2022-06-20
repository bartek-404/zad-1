
Zadanie 1 - Bartosz Michałowski
###
Instrukcja uruchomienia:
1. Klonujemy projekt <repozytorium>
2. W folderze głównym uruchamiamy komendę "docker build ."
3. Komendą docker images sprawdzamy sporawdzamy id obrazy
4. Kontener trzeba uruchomić komendą:  docker run -d -h localhost -p 80:80 <image ID>
5. Weryfikacja działania usługi  http://localhost/index.php

#Logi: 
  Sprawdzanie logów
  1. docker ps   (w celu pobrania ID)
  2. docker exec -it <container id/name> /bin/sh
  3. nano app_log.log
  
#Sprawdzenie ilości warst oraz ich wagi
  1. Ilość warst możemy sprawdzić poprzez docker history <container id>
  

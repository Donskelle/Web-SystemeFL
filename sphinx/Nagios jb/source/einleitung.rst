Einleitung
==========

Dieses Dokument soll dem Nutzer zeigen, wie das Nagios Netzwerk-Überwachungssystem installiert und konfiguriert wird

Quelle für diesen Guide ist:

http://ubuntuforums.org/showthread.php?t=1986743

Zugang
======
Nagios SSH zugriff::

   IP: 192.168.30.5
   user: nextragen
   passwort: esan
   
Nagios WebUI::

   user: nagiosadmin
   passwort: nextragen

Das Nagios hat seine eigene Mailadresse bei 1&1::
 
   adresse: nagios@nextragen.de
   passwort: Db8bZmzZ6iTxt7DVdUaR
   smtp: smtp.1und1.de
   
Vorgehensweise
==============

Vorgehensweise bei diesem Test:

#. Aufsetzen von Ubuntu Server
#. Installation und Konfiguration von Basissoftware
#. Installation von Nagios-Abhängigkeiten
#. Kompilieren und installieren von Nagios
#. Installieren von Nagios-Plugin-Abhängigkeiten
#. Installieren der Nagios-Plugins
#. Konfiguration von Nagios
    * Config-Files
    * Templates
    * Hosts / Hostgruppen
    * Services
    * Beispiel-Configs
#. Konfiguration von Linux-Servern als Client

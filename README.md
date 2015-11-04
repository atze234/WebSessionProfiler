# WebSessionProfiler
Install:
# in logbox/ and clientbox/ do
vagrant up 

# testapplication will run on 127.0.0.1:8080
# kibana on 127.0.0.1:5601
# elasticsearch api on 127.0.0.1:9100

Unified WebsessionProfiling mit Kibana - Elasticsearch - Fluentd - Syslog-NG und Apache/PHP

Ziel: 
Es soll ein Einheitliches Logging / Profiling / Tracking von Requests auf eine Webseite asynchron realisiert werden. 

Motivation: 
Es werden bereits Error Logs, Access Logs und Performance Daten (Slowquerylogs, Performance Metriken) eines Webaufrufs erhoben.
Bei Problemen auf der Webseite kann man die genannten Metriken immer nur getrennt voneinander betrachten. Gewünschtes Ziel ist, diesen Metriken einen gemeinsamen Stempel zu geben, so dass Fehlermeldungen und Performancedaten auf einen genauen Seitenaufruf reduziert angezeigt werden können. 
Das Proof of Concept ist auf PHP beschränkt, aber sicher auch mit anderen Sprachen umsetzbar

Vorgehen/nötige Anpassungen in einer Applikation:
Im php Code (am besten per prepend ab/zuschaltbar) werden verschiedene IDs vergeben. 

Mögliche Ids sind hierbei:
- Unique Request Id (WebSessionProfilerId)
- Session Id (wenn Session vorhanden, WebSessionProfilerSessionId)
- Session Step Id (WebSessionProfilerSessionInc)
- ApplikationsId (WebSessionProfilerName)

Diese IDs werden in Custom-HTTP-Response Header geschrieben, welcher mit zum Access Log geleitet wird, so dass die IDs im Accesslog mit dem Request verknüpft werden können. 
Der in der Applikation verwendete Error Writer wird angepasst, so dass die Ids dort ebenfalls mit geschrieben werden.
Zentrale SQL Klasse fügt Request IDs mit an die versendeten Queries an (Logging konfigurierbar)
Zentrale Performance Tracking Klasse enthält Ids ebenfalls.
Die verschiedenen Writer müssen so angepasst werden, dass sie Nachrichten an den lokalen syslog Prozess an verschiedene Facilities schicken. 
Syslog leitet die Nachrichten der verschiedenen Facilities an jeweils verschiedene Ports eines zentralen Logging Servers weiter, welcher mit fluentd arbeitet und die ankommenden Nachrichten ja nach Port verschieden verarbeitet und die Ergebnisse in elasticsearch speichert. 

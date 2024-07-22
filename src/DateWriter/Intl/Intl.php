<?php declare(strict_types=1);

class Intl
{
    const LONG_MONTH = [
        "de" => ["Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"],
        "en" => ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
        "es" => ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        "fr" => ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
        "pt" => ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
    ];


    const SHORT_MONTH = [
        "de" => ["Jan", "Feb", "Mär", "Apr", "Mai", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dez"],
        "en" => ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        "es" => ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
        "fr" => ["Jan", "Fév", "Mar", "Avr", "Mai", "Jun", "Jul", "Aoû", "Sep", "Oct", "Nov", "Déc"],
        "pt" => ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
    ];


    const LONG_WEEK_DAY = [
        "de" => ["Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag", "Sonntag"],
        "en" => ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"],
        "es" => ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
        "fr" => ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"],
        "pt" => ["Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado"],
    ];


    const SHORT_WEEK_DAY = [
        "de" => ["Mo", "Di", "Mi", "Do", "Fr", "Sa", "So"],
        "en" => ["sun", "mon", "tue", "wed", "thu", "fri", "sat"],
        "es" => ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
        "fr" => ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"],
        "pt" => ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
    ];


    const ORDINAL_SUFFIX = [
        "de" => ["te", "te", "te", "te"],
        "en" => ["st", "nd", "rd", "th"],
        "es" => ["º", "º", "º", "º"],
        "fr" => ["er", "e", "e", "e"],
        "pt" => ["º", "º", "º", "º"],
    ];
}

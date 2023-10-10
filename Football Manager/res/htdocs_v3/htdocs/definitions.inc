<?php

//constante pentru virsta
define('AGE_START',19);
define('AGE_END',30);
define('COEFICIENT_AGE',1);
//constante pentru virste tineri
define('Y_AGE_START', 13);
define('Y_AGE_END', 18);
define('Y_COEFICIENT_AGE',0.5);

//declaratie constante pentru portar
//G=Goalkeeper G_REFLEXES_START
define('G_REFLEXES_START', 14);
define('G_REFLEXES_END', 20);
define('G_ONEONONE_START', 14);
define('G_ONEONONE_END', 20);
define('G_HANDLING_START', 11);
define('G_HANDLING_END', 17);
define('G_COMMUNICATION_START', 8);
define('G_COMMUNICATION_END', 15);

define('G_REST_START', 1);
define('G_REST_END', 7);


//declaratie constante pentru fundas
//D=Defencer D_TACKLING_START
define('D_TACKLING_START', 14);
define('D_TACKLING_END', 20);
define('D_MARKING_START', 14);
define('D_MARKING_END', 20);
define('D_HEADING_START', 11);
define('D_HEADING_END', 17);
define('D_POSITIONING_START', 8);
define('D_POSITIONING_END', 15);

define('D_REST_START', 1);
define('D_REST_END', 7);



//declaratie constante pentru mijlocasi
//M=Midfielder M_CROSSING_START
define('M_PASSING_START', 14);
define('M_PASSING_END', 20);
define('M_CREATIVITY_START', 14);
define('M_CREATIVITY_END', 20);
define('M_CROSSING_START', 11);
define('M_CROSSING_END', 17);
define('M_LONGSHOT_START', 8);
define('M_LONGSHOT_END', 15);

define('M_REST_START', 1);
define('M_REST_END', 7);

//declaratie constante pentru atacanti
//F=Forward F_CROSSING_START
define('F_SHOOTING_START', 14);
define('F_SHOOTING_END', 20);
define('F_HEADING_START', 14);
define('F_HEADING_END', 20);
define('F_POSITIONING_START', 11);
define('F_POSITIONING_END', 17);
define('F_DRIBBLING_START', 8);
define('F_DRIBBLING_END', 15);

define('F_REST_START', 1);
define('F_REST_END', 7);


//DEFINIRE CONSTANTE GENERALE - SPEED, TEAMWORK ETC. 
//(AICI SUNT CELE CARE POT AVEA VALORI ALEATOARE INDIFERENT DE POST)
define('REST_START', 1);
define('REST_END', 50);

define('RATING_START', 5);
define('RATING_END', 25);


//definire date antrenor
define('T_AGE_START', 34);
define('T_AGE_END', 67);
define('T_VALUE_START', 5);
define('T_VALUE_END', 45);

//definire procente antrenare
//portar
//sunt definite doar cele diferite de 0
define('G_REFLEXES_PERCENT', 25);
define('G_ONEONONES_PERCENT', 25);
define('G_HANDLING_PERCENT', 25);
define('G_COMMUNICATION_PERCENT', 15);
define('G_POSITIONING_PERCENT', 10);
define('G_PASSING_PERCENT', 3);
define('G_CROSSING_PERCENT', 3);
define('G_LONGSHOTS_PERCENT', 3);

//definire procente antrenare
//fundas
//sunt definite doar cele diferite de 0
define('D_MARKING_PERCENT', 25);
define('D_TACKLING_PERCENT', 25);
define('D_HEADING_PERCENT', 15);
define('D_POSITIONING_PERCENT', 15);
define('D_PASSING_PERCENT', 5);
define('D_CROSSING_PERCENT', 5);
define('D_COMMUNICATION_PERCENT', 5);
define('D_FIRSTTOUCH_PERCENT', 5);

//definire procente antrenare
//mijlocas
//sunt definite doar cele diferite de 0
define('M_PASSING_PERCENT', 23);
define('M_CREATIVITY_PERCENT', 23);
define('M_CROSSING_PERCENT', 16);
define('M_LONGSHOTS_PERCENT', 15);
define('M_DRIBBLING_PERCENT', 5);
define('M_POSITIONING_PERCENT', 5);
define('M_TACKLING_PERCENT', 5);
define('M_MARKING_PERCENT', 5);
define('M_FIRSTTOUCH_PERCENT', 3);


//definire procente antrenare
//atacant
//sunt definite doar cele diferite de 0
define('F_SHOOTING_PERCENT', 25);
define('F_HEADING_PERCENT', 25);
define('F_POSITIONING_PERCENT', 20);
define('F_FIRSTTOUCH_PERCENT', 15);
define('F_DRIBBLING_PERCENT', 15);



//definire procente antrenare
//portar
//sunt definite doar cele diferite de 0
define('G_REFLEXES_PERCENT_1', 15);
define('G_REFLEXES_PERCENT_2', 25);
define('G_ONEONONES_PERCENT_1', 15);
define('G_ONEONONES_PERCENT_2', 25);
define('G_HANDLING_PERCENT_1', 15);
define('G_HANDLING_PERCENT_2', 25);
define('G_COMMUNICATION_PERCENT_1', 5);
define('G_COMMUNICATION_PERCENT_2', 15);
define('G_POSITIONING_PERCENT_1', 2);
define('G_POSITIONING_PERCENT_2', 10);
define('G_PASSING_PERCENT_1', 1);
define('G_PASSING_PERCENT_2', 5);
define('G_CROSSING_PERCENT_1', 1);
define('G_CROSSING_PERCENT_2', 5);
define('G_LONGSHOTS_PERCENT_1', 1);
define('G_LONGSHOTS_PERCENT_2', 5);

//definire procente antrenare
//fundas
//sunt definite doar cele diferite de 0
define('D_MARKING_PERCENT_1', 15);
define('D_MARKING_PERCENT_2', 25);
define('D_TACKLING_PERCENT_1', 15);
define('D_TACKLING_PERCENT_2', 25);
define('D_HEADING_PERCENT_1', 5);
define('D_HEADING_PERCENT_2', 15);
define('D_POSITIONING_PERCENT_1', 5);
define('D_POSITIONING_PERCENT_2', 15);
define('D_PASSING_PERCENT_1', 1);
define('D_PASSING_PERCENT_2', 5);
define('D_CROSSING_PERCENT_1', 1);
define('D_CROSSING_PERCENT_2', 5);
define('D_COMMUNICATION_PERCENT_1', 1);
define('D_COMMUNICATION_PERCENT_2', 5);
define('D_FIRSTTOUCH_PERCENT_1', 1);
define('D_FIRSTTOUCH_PERCENT_2', 5);

//definire procente antrenare
//mijlocas
//sunt definite doar cele diferite de 0
define('M_PASSING_PERCENT_1', 13);
define('M_CREATIVITY_PERCENT_1', 13);
define('M_CROSSING_PERCENT_1', 6);
define('M_LONGSHOTS_PERCENT_1', 5);
define('M_DRIBBLING_PERCENT_1', 1);
define('M_POSITIONING_PERCENT_1', 1);
define('M_TACKLING_PERCENT_1', 1);
define('M_MARKING_PERCENT_1', 1);
define('M_FIRSTTOUCH_PERCENT_1', 1);
define('M_PASSING_PERCENT_2', 23);
define('M_CREATIVITY_PERCENT_2', 23);
define('M_CROSSING_PERCENT_2', 16);
define('M_LONGSHOTS_PERCENT_2', 15);
define('M_DRIBBLING_PERCENT_2', 5);
define('M_POSITIONING_PERCENT_2', 5);
define('M_TACKLING_PERCENT_2', 5);
define('M_MARKING_PERCENT_2', 5);
define('M_FIRSTTOUCH_PERCENT_2', 3);


//definire procente antrenare
//atacant
//sunt definite doar cele diferite de 0
define('F_SHOOTING_PERCENT_1', 15);
define('F_HEADING_PERCENT_1', 15);
define('F_POSITIONING_PERCENT_1', 10);
define('F_FIRSTTOUCH_PERCENT_1', 5);
define('F_DRIBBLING_PERCENT_1', 5);
define('F_SHOOTING_PERCENT_2', 25);
define('F_HEADING_PERCENT_2', 25);
define('F_POSITIONING_PERCENT_2', 20);
define('F_FIRSTTOUCH_PERCENT_2', 15);
define('F_DRIBBLING_PERCENT_2', 15);





//constante pentru vmaxpos
//pentru calculul valorii maxime la care poate ajunge un jucator
//dc e SS: 2.5...

//redefinire - ce-i sus, obsolete!
//SS-poate ajunge la 50, fac un random intre 47 si 50
//VT-poate ajunge la 43, fac un random intre 39 si 43
//CB-poate ajunge la 39, fac random intre 35 si 40
//OC-36, random intre 31-37
//WN-30, random intre 24-32
//daca ajunge la acele valori, poate sa creasca cu o probabilitate de 1/8 (o sansa din 8)

define('CST_SS', 2.5);
define('CST_VT', 2.2);
define('CST_CB', 1.9);
define('CST_OC', 1.7);
define('CST_WN', 1.5);

define('CST_SSnou', 50);
define('CST_VTnou', 43);
define('CST_CBnou', 39);
define('CST_OCnou', 36);
define('CST_WNnou', 30);



//coeficientul de antrenament
define('TRAINING_EASY', 0.7);
define('TRAINING_NORMAL', 1);
define('TRAINING_HARD', 1.3);
define('TRAINING_VERYHARD', 1.7);

//constante pentru SALT
//in zile
//cind se genereaza jucatorul, se introduc aceste cst in tabela SALT
define('CST_TAL_SS', 4);
define('CST_TAL_VT', 8);
define('CST_TAL_CB', 12);
define('CST_TAL_OC', 16);
define('CST_TAL_WN', 20);


?>
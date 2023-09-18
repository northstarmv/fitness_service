<?php

/** @var \Laravel\Lumen\Routing\Router $router */


//BMI and PI
$router->post('/{user_id}/bmi-pi','HealthDataController@saveBMIPIData');
//FAT and MM
$router->post('/{user_id}/fat-mm','HealthDataController@saveBodyFatMassData');
//BloodSugar
$router->post('/{user_id}/blood-sugar','HealthDataController@saveBloodSugarData');
//BloodPressure
$router->post('/{user_id}/blood-pressure','HealthDataController@saveBloodPressureData');
//Macros
$router->post('/{user_id}/macros','MacroDataController@saveMacroProgram');
$router->post('/{user_id}/override-macros','MacroDataController@overrideMacros');
$router->post('/{user_id}/override-adv-macros','MacroDataController@overrideAdvMacros');
//CommonData
$router->post('/{user_id}/misc','HealthDataController@saveMiscData');

//OverViews
$router->get('/{user_id}/fitness-data','HealthDataController@getFitnessData');
$router->get('/{user_id}/home/fitness-data/{day}','HealthDataController@getHomeFitnessData');
$router->get('/{trainer_id}/all-macros-data','MacroDataController@getAllUserMacrosData');

//User Meals
$router->post('/{user_id}/meals','UserFoodDataController@addMeal');
$router->post('/{user_id}/delete-meals','UserFoodDataController@deleteMeal');
$router->get('/{user_id}/meals','UserFoodDataController@getUserDailyMeals');
$router->get('/{user_id}/weekly-meals','UserFoodDataController@getUserWeeklyMeals');
$router->get('/trainer/{trainer_id}/meals','UserFoodDataController@getAllUserDailyMealsOverview');
$router->get('/user-meals/last-thirty-days/{user_id}','UserFoodDataController@getLastThirtyDayMeals');

//User Health Charts
$router->post('/charts/health-charts','UserHealthChartsController@userHealthCharts');

//Workout Presets
$router->post('/workout-presets/add-or-update','WorkOutPresetsController@newOrEditWorkoutPreset');
$router->post('/workout-presets/delete','WorkOutPresetsController@deleteWorkoutPreset');
$router->post('/workout-presets/get','WorkOutPresetsController@getWorkoutPresets');
$router->get('/workout-presets/search/{search_key}','WorkOutPresetsController@searchWorkoutPresets');

//User Workouts
$router->get('/workouts/common/get/{workout_id}','UserWorkoutsController@getWorkout');
$router->post('/workouts/save/{trainer_id}/{user_id}','UserWorkoutsController@saveWorkout');
$router->post('/workouts/add-new','UserWorkoutsController@addNew');
$router->post('/workouts/complete','UserWorkoutsController@completeWorkout');
$router->post('/workouts/finish','UserWorkoutsController@finishWorkout');
$router->post('/workouts/delete/{workout_id}','UserWorkoutsController@deleteWorkout');
$router->get('/workouts/trainer/{trainer_id}','UserWorkoutsController@getWorkoutsTrainer');
$router->get('/finished-workouts/trainer/{trainer_id}','UserWorkoutsController@getFinishedWorkoutsTrainer');
$router->get('/workouts/client/{user_id}','UserWorkoutsController@getWorkoutsClient');
//Foods
$router->get('/foods/{search_key}','FoodController@searchFood');
$router->get('/get-all-food','FoodController@getAllFoods');
$router->post('/foods/actions/new','FoodController@newFood');
$router->post('/foods/actions/edit','FoodController@editFood');
$router->post('/foods/actions/delete','FoodController@deleteFood');

//Workouts
$router->get('/workouts/{search_key}','WorkoutsController@searchWorkouts');
$router->post('/workouts/actions/new','WorkoutsController@addWorkout');
$router->get('/workouts/actions/get-all','WorkoutsController@getAllWorkouts');
$router->post('/workouts/actions/update','WorkoutsController@updateWorkout');
$router->post('/workouts/actions/delete','WorkoutsController@deleteWorkout');



//Health Services
$router->post('/static/health-services/bmi-pi','HealthServicesController@getBMIPIData');
$router->post('/static/health-services/fat-mm','HealthServicesController@getBodyFatMassData');
$router->post('/static/health-services/blood-sugar','HealthServicesController@getBloodSugarData');
$router->post('/static/health-services/blood-pressure','HealthServicesController@getBloodPressureData');

//Resources
$router->post('/resources/actions/search','ResourceController@searchResourceData');
$router->get('/resources/actions/get','ResourceController@GetResources');
$router->post('/resources/actions/add-or-update','ResourceController@AddOrUpdateResource');
$router->post('/resources/actions/delete','ResourceController@DeleteResource');

//Newsletters
$router->post('/newsletters/actions/search','newsLetterController@searchNewsletter');
$router->get('/newsletters/actions/get','newsLetterController@GetNewsletters');
$router->post('/newsletters/actions/add-or-update','newsLetterController@AddOrUpdateNewsletter');
$router->post('/newsletters/actions/delete','newsLetterController@DeleteNewsletter');


//Prescriptions
$router->post('/prescriptions/actions/save','UserPrescriptionsController@savePrescription');
$router->get('/prescriptions/actions/get/{user_id}','UserPrescriptionsController@getPrescription');
$router->post('/prescriptions/actions/archive/{id}','UserPrescriptionsController@archivePrescription');

//Exclusive Gyms
$router->post('/exclusive-gyms/actions/new-schedule','ExclusiveGymScheduleController@createSchedule');
$router->post('/exclusive-gyms/actions/confirm-schedule','ExclusiveGymScheduleController@confirmGymSchedules');
$router->post('/exclusive-gyms/actions/get-unconfirmed-schedule','ExclusiveGymScheduleController@getMyUnconfirmedBookings');
$router->post('/exclusive-gyms/actions/delete-unconfirmed-schedule','ExclusiveGymScheduleController@deleteMyUnconfirmedSchedule');
$router->post('/exclusive-gyms/actions/delete-all-unconfirmed-schedule','ExclusiveGymScheduleController@deleteAllMyUnconfirmedSchedule');
$router->post('/exclusive-gyms/actions/get-availability','ExclusiveGymScheduleController@getAvailableTimeSlots');


$router->get('/exclusive-gyms/actions/get-my-schedules/{user_id}','ExclusiveGymScheduleController@getMyBookings');

//Commercial Gyms
$router->post('/commercial-gyms/actions/new-subscription','CommercialGymSubscriptionController@newComGymSub');
$router->get('/commercial-gyms/actions/get-my-schedules/{user_id}','CommercialGymSubscriptionController@getMyComGymSubs');
//Lock
$router->post('/lock/actions/unlock','LockController@Unlock');

//LabReports
$router->post('/lab-reports/actions/save','LabReportController@saveLabReport');
$router->post('/lab-reports/actions/get','LabReportController@getMyReports');
$router->post('/lab-reports/actions/delete','LabReportController@deleteMyReports');

//Client Notes
$router->post('/client-notes/actions/save','ClientNotesController@createClientNote');
$router->post('/client-notes/actions/get','ClientNotesController@getClientNotes');
$router->post('/client-notes/actions/toggle','ClientNotesController@toggleClientNote');
$router->post('/client-notes/actions/delete','ClientNotesController@deleteClientNote');

//Common Events Calender
$router->post('/common-events/get-all/{user_role}/{user_id}','CommonEventsController@getCommonEvents');

//WatchData
$router->post('/watch-data/actions/save','UserWatchDataController@CreateOrUpdateData');
$router->post('/watch-data/actions/get','UserWatchDataController@GetData');

//DietConsultation
$router->post('/diet-consultation/actions/save','DietConsultationsController@addDietConsult');
$router->get('/diet-consultation/actions/get/{user_id}','DietConsultationsController@getDietConsults');

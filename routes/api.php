<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlannerController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\RequirementsController;
use App\Http\Controllers\PlacesController;
use App\Http\Controllers\EventsPlacesController;
use App\Http\Controllers\PlannerHasPlacesController;
use App\Http\Controllers\PlannerHasEventsController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\SpecialRequirementController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Mail;
use App\Mail\test;


// user routes
Route::post('/search/user'   , [UserController::class    , 'search']);//user searchs about planner
Route::post('/register', [UserController::class, 'register']); //done
Route::post('/login'   , [UserController::class,    'login']);//done
Route::group([
   'prefix' => 'auth',
   'middleware' => 'api']
   , function ($router)
{
Route::post  ('/logout'                         , [UserController::class, 'logout'            ]);//done
Route::post  ('/refresh'                        , [UserController::class, 'refresh'           ]);//done
Route::get   ('/user-profile'                   , [UserController::class, 'userProfile'       ]);//done
Route::get   ('events/{eventId}/places'         , [EventsPlacesController::class, 'showPlaces']);//done
Route::post  ('create/requirements/{id}'        , [RequirementsController::class, 'store'     ]);
Route::post  ('/update/requirements/{id}'       , [RequirementsController::class, 'update'    ]);
Route::delete('delete/requirements/{id}'        , [RequirementsController::class, 'destroy'   ]);
Route::post  ('create/Guests/{id}'              , [GuestController::class, 'store'            ]);
Route::post  ('/update/Guests/{id}'             , [GuestController::class, 'update'           ]);
Route::delete('delete/Guests/{id}'              , [GuestController::class, 'destroy'          ]);
Route::post  ('create/SpecialRequirement/{id}'  , [SpecialRequirementController::class, 'store'  ]);
Route::post   ('/update/SpecialRequirement/{id}' , [SpecialRequirementController::class, 'update' ]);
Route::delete('delete/SpecialRequirement/{id}'  , [SpecialRequirementController::class, 'destroy']);
// get all places for a planner in an event
Route::get('/events/{idevents}/planners/{plannerId}/places', [PlannerHasPlacesController::class,'getPlannerEventPlaces']);
// add place to planner's places
Route::post('/events/{idevents}/planners/{plannerId}/places', [PlannerHasPlacesController::class, 'addPlaceToPlannerInEvent']);



}
);
// planner routes
Route::post('/planner/register', [PlannerController::class, 'register']);//done
Route::post('/planner/login'   , [PlannerController::class, 'login'   ]);//done
Route::group([
    'middleware'=>['auth:planner']

   ]
   , function ($router)
    {
      Route::post  ('/planner/refresh'                  ,[PlannerController::class, 'refresh'    ]);//done
      Route::post  ('/planner/logout'                   ,[PlannerController::class, 'logout'     ]);//done
      Route::get   ('/planner-profile'                  ,[PlannerController::class, 'userProfile']);//done
      Route::post  ('/places'                           ,[PlacesController::class, 'create'      ]);//done
      Route::post  ('/create/events'                    ,[EventsController::class, 'store'       ]); //done
      Route::post  ('/places/{id}'                      ,[PlacesController::class, 'update'      ]);//done
      Route::delete('/places/{id}'                      ,[PlacesController::class, 'destroy'     ]);//done
      Route::post  ('events/{id}'                       ,[EventsController::class, 'update'      ]); //done
      Route::delete('delete/events/{id}'                ,[EventsController::class, 'destroy'     ]); //done
      Route::post  ('search/planner'                    ,[PlannerController::class , 'search']);//planner searchs about user
      Route::get   ('show/all/SpecialRequirement'       ,[SpecialRequirementController::class, 'index'    ]);
      Route::post  ('/create/order/{idpending}'         ,[OrdersController::class  ,'create_order'        ]);
      Route::post  ('handle-order-request/{id}'         ,[PlannerController::class ,'handleorderRequest'  ]);
      Route::get   ('show/all/Pending_order/{idplanner}',[PlannerController::class ,'showAllPending_order'  ]);

    }
);
  //راوتات مشتركة
  Route::get('show/all/planners'    , [usercontroller::class, 'index'  ]);
//events routes
Route::get('/Show/events'           , [EventsController::class, 'index']); //done
Route::get('/one/events/{id}'       , [EventsController::class, 'show']); //done
//places routes
Route::get('/places'                     , [PlacesController::class, 'index'       ]);//done
Route::get('/places/{id}'                , [PlacesController::class, 'show'        ]);//done
Route::get('/places/{placeId}/events'    , [PlacesController::class, 'showEvents'  ]);
Route::get('/places/{placeId}/planners'  , [PlacesController::class, 'showPlanners']);
// rate routes
Route::post('/rates/user'   ,[RateController::class, 'store'  ]);//done
Route::post('/rates/planner',[RateController::class, 'store_planner'  ]);//done
/////////////
Route::get('/rates/{rate}'         , [RateController::class, 'show'   ]);
Route::post('/rates/{rate}/confirm', [RateController::class, 'confirm']);
Route::put('/rates/{rate}'         , [RateController::class, 'update' ]);
Route::delete('/rates/{rate}'      , [RateController::class, 'destroy']);
//////////////////////////////////////////////////////
// Requirements routes
Route::get('show/all/requirements'     , [RequirementsController::class, 'index'  ]);
Route::get('show/one/requirements/{id}', [RequirementsController::class, 'show'   ]);
//////////////////////////////////////////////////////
// Guests routes
Route::get('show/all/Guests/{id}'      , [GuestController::class, 'index'  ]);
Route::get('show/one/Guests/{idguest}' , [GuestController::class, 'show'   ]);
///////////////////////////////////////////////////////
// SpecialRequirement routes
Route::get('show/one/SpecialRequirement/{id}', [SpecialRequirementController::class, 'show'   ]);
//////////////////////////////////////////////////////
//verification_code routes
Route::post('/send-verification-code', [VerificationController::class, 'sendVerificationCode']);
Route::post('/verify-code'           , [VerificationController::class, 'verifyCode'          ]);
//////////////////////////////////////////////////////
//companies routes
Route::get('/companies'        , [CompanyController::class, 'index'  ]);
Route::get('/companies/{id}'   , [CompanyController::class, 'show'   ]);
Route::post('/companies'       , [CompanyController::class, 'store'  ]);
Route::POST('/companies/{id}'   , [CompanyController::class, 'update' ]);
Route::delete('/companies/{id}', [CompanyController::class, 'destroy']);
Route::delete('/companies/delete/planner/{id}'                   , [CompanyController::class, 'destroy_planner']);
      Route::post  ('company/events/{eventId}/places'             ,[EventsPlacesController::class, 'addPlaceToEvent'    ]);//done
      Route::delete('company/events/{eventId}/places/{placeId}'   ,[EventsPlacesController::class,'removePlaceFromEvent']);//done
      Route::post('company/planner/{plannerId}/events'            ,[PlannerHasEventsController::class, 'addEventToPlanner']);
      Route::delete('company/planner/{plannerid}/event/{eventid}' ,[EventsPlacesController::class,'removePlaceFromEvent']);//done
      Route::get('company/planner/{plannerId}/events'             ,[PlannerHasEventsController::class, 'getPlannerEvents']);

/////////////////////////////////////////////////////////
// Company routes
Route::post('/company/send-reset-password-code', [CompanyController::class, 'sendResetPasswordCode']);
Route::post('/company/reset-password'          , [CompanyController::class, 'resetPassword'        ]);
////////////////////////////////////////////////////////
// Planner routes
Route::post('/planner/send-reset-password-code', [PlannerController::class,'sendResetPasswordCode']);
Route::post('/planner/reset-password'          , [PlannerController::class,'resetPassword'        ]);
////////////////////////////////////////////////////////
// User routes
Route::post('/user/send-reset-password-code',[UserController::class,'sendResetPasswordCode']);
Route::post('/user/reset-password'          ,[UserController::class,'resetPassword'        ]);
///////////////////////////////////////////////////////
//search olaces
Route::post('/search/places' ,[PlacesController::class  , 'search']);//planner and user search about placess


// مسار لإرسال طلب الحدث من قبل المستخدم  done
Route::post('/send-event-place-request/{user_id}/{event_id}/{place_id}/{planner_id}',[UserController::class, 'sendEventRequest'])->middleware(['auth']);
Route::get ('show/first_order/{iduser}'         ,[UserController::class    ,'show_Pending_order'       ]);
Route::get('Status_of_my_order/{iduser}'        ,[UserController::class    ,'Status_of_my_order'       ]);
Route::post('/send-event-details'               ,[UserController::class    ,'sendEventDetails'])->middleware(['auth']);

Route::get ('send/email' ,function(){
    Mail::to(users: 'omarzenaldeen50@gmail.com')->send(new test());
    return "email send";
    // Mail::to('omarzenalden50@gmail.com')->send(new \App\Mail\Test("Hello, World!"));
});

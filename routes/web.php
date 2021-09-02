<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/** FRONTEND ROUTES **/
//Maintenace Route
Route::get('maintenance', 'T_LoginController@maintenance');

//Login Routes
Route::get('/', 'T_LoginController@login');
Route::post('login', 'T_LoginController@handleLogin');
Route::get('logout', 'T_LoginController@logout');
//Register Routes
Route::get('register', 'T_LoginController@register');
Route::post('register', 'T_LoginController@save');

Route::group(['middleware' => 'auth'], function() {
	/** BACKEND ROUTES **/
	//Dashboard
	Route::get('dashboard', 'T_DashboardController@index');
	Route::get('sample', 'T_DashboardController@sample');

	//My Profile Routes
	Route::get('my_profile/{id}', 'T_AccountController@edit');
	Route::post('profile/save', 'T_AccountController@save');

	// -- Admin Only
	Route::group(['middleware' => 'admin'], function() {
		//Site Settings Routes
		Route::get('settings', 'T_SettingsController@view');
		Route::post('settings/save', 'T_SettingsController@save');

		//Report Routes
		//Route::post('accounts/user_logs', 'T_ReportController@userLogs');
		Route::post('user_statistics/download', 'T_ReportController@userStatReport');
		Route::get('report/cost_reduction', 'T_ReportController@costReduction');
		Route::post('report/cost_reduction/show', 'T_ReportController@costredReport');
		Route::get('report/utilization', 'T_ReportController@utilizationIndex');
		Route::post('report/utilization/download', 'T_ReportController@downloadPDF');
		
		//User Group Routes
		Route::get('user_groups', 'T_UserGroupController@index');
		Route::get('user_groups/search', 'T_UserGroupController@search');
		Route::post('user_groups/search', 'T_UserGroupController@search');
		Route::get('user_groups/edit/{id}', 'T_UserGroupController@edit');
		Route::post('user_groups/save', 'T_UserGroupController@save');

		//Document Type Routes
		Route::get('document_types', 'T_DocumentTypeController@index');
		Route::get('document_types/search', 'T_DocumentTypeController@search');
		Route::post('document_types/search', 'T_DocumentTypeController@search');
		Route::get('document_types/add', 'T_DocumentTypeController@add');
		Route::get('document_types/edit/{id}', 'T_DocumentTypeController@edit');
		Route::post('document_types/save', 'T_DocumentTypeController@save');

		//Statistics Routes
		Route::get('document_statistics', 'T_StatisticsController@overAll');
		Route::get('document_statistics/filter', 'T_StatisticsController@overAll');
		Route::post('document_statistics/filter', 'T_StatisticsController@overAll');
		Route::get('user_statistics', 'T_StatisticsController@userIndex');
		Route::get('user_statistics/{id}', 'T_StatisticsController@userView');
		Route::get('user_statistics/{id}/filter', 'T_StatisticsController@userView');
		Route::post('user_statistics/{id}/filter', 'T_StatisticsController@userView');
		
		Route::get('user_statistics/search', 'T_StatisticsController@search');
		Route::post('user_statistics/search', 'T_StatisticsController@search');		
		Route::get('unit_statistics', 'T_StatisticsController@unitIndex');
		Route::get('unit_statistics/search', 'T_StatisticsController@search');
		Route::post('unit_statistics/search', 'T_StatisticsController@search');
		Route::get('unit_statistics/{id}', 'T_StatisticsController@unitView');
		//Route::get('unit_statistics/unitSearch', 'T_StatisticsController@search');
		//Route::post('unit_statistics/unitSearch', 'T_StatisticsController@search');

		//Action Settings Routes
		Route::get('action_settings', 'T_ActionController@index');
		Route::get('action_settings/add', 'T_ActionController@add');
		Route::get('action_settings/edit/{id}', 'T_ActionController@edit');
		Route::post('action_settings/save', 'T_ActionController@save');

		Route::get('zoom_settings/{id}', 'T_ZoomSettingsController@edit');
		Route::post('zoom_settings/save', 'T_ZoomSettingsController@save');
	});
	// -- End of Admin Only

	//Report Routes
	Route::get('report/print', 'T_ReportController@printMain');
	Route::get('report', 'T_ReportController@index');
	Route::get('report/search', 'T_ReportController@search');
	Route::post('report/search', 'T_ReportController@search');

	//My Document Routes
	Route::get('my_incoming', 'T_IncomingController@index');
	Route::get('my_incoming/search', 'T_IncomingController@search');
	Route::post('my_incoming/search', 'T_IncomingController@search');
	Route::get('my_outgoing', 'T_OutgoingController@index');
	Route::get('my_outgoing/search', 'T_OutgoingController@search');
	Route::post('my_outgoing/search', 'T_OutgoingController@search');

	//My Pending Route - DC and RD
	Route::get('my_pending', 'T_IncomingController@pending');
	Route::get('my_pending/search', 'T_IncomingController@pendingSearch');
	Route::post('my_pending/search', 'T_IncomingController@pendingSearch');

	//Summary for RD and DC
	Route::get('rush', 'T_DocumentController@rushIndex');

	//Account Routes
	Route::get('accounts', 'T_AccountController@index');
	Route::get('accounts/search', 'T_AccountController@search');
	Route::post('accounts/search', 'T_AccountController@search');
	Route::get('accounts/add', 'T_AccountController@add');
	Route::get('accounts/view/{id}', 'T_AccountController@view');
	Route::get('accounts/edit/{id}', 'T_AccountController@edit');
	Route::post('accounts/save', 'T_AccountController@save');
	Route::get('accounts/unapproved', 'T_AccountController@unapproved');
	Route::get('accounts/deactivate/{id}', 'T_AccountController@deactivate');
	Route::get('accounts/reset/{id}', 'T_AccountController@resetPassword');

	//Group Routes
	Route::get('groups', 'T_GroupController@index');
	Route::get('groups/search', 'T_GroupController@search');
	Route::post('groups/search', 'T_GroupController@search');
	Route::get('groups/add', 'T_GroupController@add');
	Route::get('groups/view/{id}', 'T_GroupController@view');
	Route::get('groups/edit/{id}', 'T_GroupController@edit');
	Route::post('groups/save', 'T_GroupController@save');
	Route::get('groups/member/delete/{id}', 'T_GroupController@deleteMember');

	//Company Information Routes
	Route::get('company', 'T_CompanyController@index');
	Route::get('company/search', 'T_CompanyController@search');
	Route::post('company/search', 'T_CompanyController@search');
	Route::get('company/add', 'T_CompanyController@add');
	Route::get('company/view/{id}', 'T_CompanyController@view');
	Route::get('company/edit/{id}', 'T_CompanyController@edit');
	Route::post('company/save', 'T_CompanyController@save');

	//Event Routes
	Route::get('events', 'T_EventController@index');
	Route::get('events/search', 'T_EventController@search');
	Route::post('events/search', 'T_EventController@search');
	Route::get('events/add', 'T_EventController@add');
	Route::get('events/view/{id}', 'T_EventController@view');
	Route::get('events/edit/{id}', 'T_EventController@edit');
	Route::post('events/save', 'T_EventController@save');
	Route::get('events/types', 'T_EventController@types');
	Route::get('events/venues', 'T_EventController@venues');
	Route::get('events/delete/{id}', 'T_EventController@delete');
	Route::get('events/view/delete-attendee/{id}', 'T_EventController@deleteAttendee');
	Route::post('events/print_document', 'T_EventController@printDocument');
	Route::get('events/calendar', 'T_EventController@calendarView');
	Route::get('events/confirmation', 'T_EventController@confirmation');
	Route::get('events/invitations', 'T_EventController@invitations');
	Route::get('events/view/delete-file/{id}', 'T_EventController@deleteFile');

	//Incoming Document Routes - Special Routes
	Route::get('incoming/add', 'T_IncomingController@add');
	Route::get('incoming/route/{id}', 'T_IncomingController@route');
	Route::get('incoming/edit/{id}', 'T_IncomingController@edit');
	Route::post('incoming/save', 'T_IncomingController@save');
	Route::get('incoming/senders', 'T_IncomingController@senders');
	Route::get('incoming/companies', 'T_IncomingController@companies');
	Route::get('unrouted', 'T_IncomingController@unrouted');
	Route::post('unrouted/search', 'T_IncomingController@unroutedSearch');
	Route::get('incoming/remove-tag/{id}', 'T_IncomingController@removeTag');
	Route::get('incoming/attachment_delete/{id}', 'T_IncomingController@removeAttachment');
	Route::get('incoming/delete/{id}', 'T_IncomingController@deleteDocument');

	//Incoming Document Routes - Normal
	Route::get('incoming', 'T_IncomingController@index');
	Route::get('incoming/search' ,'T_IncomingController@search');
	Route::post('incoming/search', 'T_IncomingController@search');
	Route::get('incoming/view/{id}', 'T_IncomingController@view');
	Route::get('incoming/reply/{id}', 'T_IncomingController@reply');
	Route::post('incoming/reply/{id}', 'T_IncomingController@saveReply');
	Route::get('pending', 'T_IncomingController@pending');
	Route::get('pending/search', 'T_IncomingController@pendingSearch');
	Route::post('pending/search', 'T_IncomingController@pendingSearch');
	Route::post('incoming/print_document', 'T_IncomingController@printDocument');
	Route::get('incoming/unseen', 'T_IncomingController@unseen');

	//Incoming Document Routes - User Group ID 5: Unit DC
	Route::get('incoming/encoded', 'T_IncomingController@encoded');
	Route::post('incoming/encoded', 'T_IncomingController@encoded');

	//Incoming Document Routes - User Group ID 2
	Route::get('incoming_routed', 'T_IncomingController@thruIndex');
	Route::get('incoming_routed/search', 'T_IncomingController@thruSearch');
	Route::post('incoming_routed/search', 'T_IncomingController@thruSearch');

	//Action Done Routes
	Route::get('actions', 'T_ActionDoneController@index');
	Route::get('actions/read', 'T_ActionDoneController@actionRead');
	Route::get('actions/search', 'T_ActionDoneController@search');
	Route::post('actions/search', 'T_ActionDoneController@search');

	//Outgoing Document Routes
	Route::get('outgoing', 'T_OutgoingController@index');
	Route::get('outgoing/search', 'T_OutgoingController@search');
	Route::post('outgoing/search', 'T_OutgoingController@search');
	Route::get('outgoing/add', 'T_OutgoingController@add');
	Route::get('outgoing/view/{id}', 'T_OutgoingController@view');
	Route::get('outgoing/edit/{id}', 'T_OutgoingController@edit');
	Route::post('outgoing/save', 'T_OutgoingController@save');
	Route::get('outgoing/view/remove-tag/{id}', 'T_OutgoingController@removeTag');
	Route::get('outgoing/attachment_delete/{id}', 'T_OutgoingController@deleteAttachment');
	Route::get('outgoing/addressees', 'T_OutgoingController@addressees');
	Route::get('outgoing/companies', 'T_OutgoingController@companies');
	Route::post('outgoing/print_document', 'T_OutgoingController@printDocument');
	Route::get('outgoing/loremipsum/{id}', 'T_OutgoingController@loremipsum');

	//RD's Schedule Routes
	Route::get('rd_schedule', 'T_ScheduleController@index');
	Route::get('rd_schedule/add', 'T_ScheduleController@add');
	Route::get('rd_schedule/view/{id}', 'T_ScheduleController@view');
	Route::get('rd_schedule/edit/{id}', 'T_ScheduleController@edit');
	Route::get('rd_schedule/approve/{id}', 'T_ScheduleController@approve');
	Route::post('rd_schedule/postpone', 'T_ScheduleController@postpone');
	Route::post('rd_schedule/save', 'T_ScheduleController@save');
	Route::post('rd_schedule/cancel', 'T_ScheduleController@cancel');
	Route::get('rd_schedule/remove-participant/{id}', 'T_ScheduleController@remove');
	Route::get('rd_schedule/destinations', 'T_ScheduleController@destinations');

	//Scheduled Meetings Routes
	Route::get('meetings', 'T_MeetingController@index');
	Route::get('meetings/search', 'T_MeetingController@search');
	Route::post('meetings/search', 'T_MeetingController@search');	
	Route::get('meetings/unapproved', 'T_MeetingController@unapproved');
	
	//Unseen Comment Routes
	Route::get('document_comments', 'T_CommentController@documentIndex');
	Route::get('document_comments/read', 'T_CommentController@documentRead');
	Route::get('event_comments', 'T_CommentController@eventIndex');
	Route::get('event_comments/read', 'T_CommentController@eventRead');

	//Statistics Routes
	Route::get('my_statistics', 'T_StatisticsController@myStatistics');
	Route::get('my_statistics/filter', 'T_StatisticsController@myStatistics');
	Route::post('my_statistics/filter', 'T_StatisticsController@myStatistics');

	//Zoom Settings Rotues
	Route::get('zoom_schedules', 'T_ZoomController@index');
	Route::get('zoom_schedules/approval', 'T_ZoomController@index');
	Route::get('zoom_schedules/edit/{id}', 'T_ZoomController@edit');
	Route::post('zoom_schedules/save', 'T_ZoomController@save');

	//About Routes
	Route::get('about', 'T_AboutController@index');

	//Email Routes
	Route::get('email', 'T_EmailController@index');

	//FOR MANAGEMENT REVIEW
	Route::get('review', 'T_StatisticsController@review');

	Route::get('pending_download', 'T_ReportController@downloadPending');

});
<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// lib
include 'lib/AB_Config.php';
include 'lib/AB_Controller.php';
include 'lib/AB_Entity.php';
include 'lib/AB_Form.php';
include 'lib/AB_Query.php';
include 'lib/AB_SMS.php';
include 'lib/AB_Validator.php';
// lib/curl
include 'lib/curl/curl.php';
include 'lib/curl/curl_response.php';
// lib/entities
include 'lib/entities/AB_Appointment.php';
include 'lib/entities/AB_Category.php';
include 'lib/entities/AB_Coupon.php';
include 'lib/entities/AB_Customer.php';
include 'lib/entities/AB_CustomerAppointment.php';
include 'lib/entities/AB_Holiday.php';
include 'lib/entities/AB_SentNotification.php';
include 'lib/entities/AB_Notification.php';
include 'lib/entities/AB_Payment.php';
include 'lib/entities/AB_ScheduleItemBreak.php';
include 'lib/entities/AB_Service.php';
include 'lib/entities/AB_Staff.php';
include 'lib/entities/AB_StaffScheduleItem.php';
include 'lib/entities/AB_StaffService.php';
// lib/utils
include 'lib/utils/AB_DateTimeUtils.php';
include 'lib/utils/AB_Instance.php';
include 'lib/utils/AB_Utils.php';
// backend/modules
include 'backend/modules/appearance/AB_AppearanceController.php';
include 'backend/modules/calendar/AB_CalendarController.php';
include 'backend/modules/calendar/forms/AB_AppointmentForm.php';
include 'backend/modules/coupons/AB_CouponsController.php';
include 'backend/modules/custom_fields/AB_CustomFieldsController.php';
include 'backend/modules/customer/AB_CustomerController.php';
include 'backend/modules/customer/forms/AB_CustomerForm.php';
include 'backend/modules/notifications/AB_NotificationsController.php';
include 'backend/modules/notifications/forms/AB_NotificationsForm.php';
include 'backend/modules/payment/AB_PaymentController.php';
include 'backend/modules/service/AB_ServiceController.php';
include 'backend/modules/service/forms/AB_CategoryForm.php';
include 'backend/modules/service/forms/AB_ServiceForm.php';
include 'backend/modules/settings/AB_SettingsController.php';
include 'backend/modules/settings/forms/AB_CompanyForm.php';
include 'backend/modules/settings/forms/AB_BusinessHoursForm.php';
include 'backend/modules/sms/AB_SmsController.php';
include 'backend/modules/staff/AB_StaffController.php';
include 'backend/modules/staff/forms/AB_StaffMemberForm.php';
include 'backend/modules/staff/forms/AB_StaffMemberEditForm.php';
include 'backend/modules/staff/forms/AB_StaffMemberNewForm.php';
include 'backend/modules/staff/forms/AB_StaffScheduleForm.php';
include 'backend/modules/staff/forms/AB_StaffScheduleItemBreakForm.php';
include 'backend/modules/staff/forms/AB_StaffServicesForm.php';
include 'backend/modules/staff/forms/widget/AB_TimeChoiceWidget.php';
include 'backend/modules/tinymce/AB_TinyMCE_Plugin.php';
include 'backend/modules/appointments/AB_AppointmentsController.php';
// frontend/modules
include 'frontend/modules/booking/AB_BookingController.php';
include 'frontend/modules/booking/lib/AB_UserBookingData.php';
include 'frontend/modules/booking/lib/AB_AvailableTime.php';
include 'frontend/modules/customer_profile/AB_CustomerProfileController.php';

include 'backend/AB_Backend.php';
include 'frontend/AB_Frontend.php';
include 'installer.php';
include 'updates.php';
<?php

namespace App\Http\Controllers\Admin;

use App\Models\NotificationTemplateLangs;
use App\Models\NotificationTemplates;
use App\Models\Utility;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\ActivityLogger;

class NotificationTemplatesController extends Controller
{

    use ActivityLogger;

    public function index($id = null, $lang = 'en')
    {
        if (Auth::user()->can('edit email template')) {
            $usr = \Auth::user();


            $notification_templates = NotificationTemplates::all();

            return view('admin.notification_templates.index', compact('notification_templates'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit email template')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'content' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $NotiLangTemplate = NotificationTemplateLangs::where('parent_id', '=', $id)->where('lang', '=', $request->lang)->where('created_by', '=', \Auth::user()->creatorId())->first();

            // if record not found then create new record else update it.
            if (empty($NotiLangTemplate)) {
                $variables = NotificationTemplateLangs::where('parent_id', '=', $id)->where('lang', '=', $request->lang)->first()->variables;

                $NotiLangTemplate            = new NotificationTemplateLangs();
                $NotiLangTemplate->parent_id = $id;
                $NotiLangTemplate->lang      = $request['lang'];
                $NotiLangTemplate->content   = $request['content'];
                $NotiLangTemplate->variables = $variables;
                $NotiLangTemplate->created_by = \Auth::user()->creatorId();
                $NotiLangTemplate->save();
            } else {
                $NotiLangTemplate->content = $request['content'];
                $NotiLangTemplate->save();
            }

            return redirect()->route(
                'manage.notification.language',
                [
                    $id,
                    $request->lang,
                ]
            )->with('success', __('Notification Template successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function manageNotificationLang($id = null, $lang = 'en')
    {
        if (Auth::user()->can('edit email template')) {
            if ($id != null) {
                $notification_template     = NotificationTemplates::where('id', $id)->first();
            } else {
                $notification_template     = NotificationTemplates::first();
            }
            if (empty($notification_template)) {
                return redirect()->back()->with('error', __('Not exists in notification template.'));
            }
            $languages         = Utility::languages();
            $curr_noti_tempLang = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', $lang)->where('created_by', '=', \Auth::user()->creatorId())->first();
            if (!isset($curr_noti_tempLang) || empty($curr_noti_tempLang)) {
                $curr_noti_tempLang       = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', $lang)->first();
            }
            if (!isset($curr_noti_tempLang) || empty($curr_noti_tempLang)) {
                $curr_noti_tempLang       = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', 'en')->first();
                !empty($curr_noti_tempLang) ? $curr_noti_tempLang->lang = $lang : null;
            }
            $notification_templates = NotificationTemplates::all();
            return view('admin.notification_templates.show', compact('notification_template', 'notification_templates', 'curr_noti_tempLang', 'languages'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show()
    {
        return redirect()->back();
    }
}

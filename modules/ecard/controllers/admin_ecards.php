<?php defined("SYSPATH") or die("No direct script access.");
/**
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2010 Bharat Mediratta
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street - Fifth Floor, Boston, MA  02110-1301, USA.
 */
class Admin_ecards_Controller extends Admin_Controller {
  public function index() {
    $view = new Admin_View("admin.html");
    $view->page_title = t("eCard settings");
    $view->content = new View("admin_ecards.html");
    $view->content->form = $this->_get_admin_form();
    print $view;
  }

  public function save() {
    access::verify_csrf();
    $form = $this->_get_admin_form();
    $form->validate();
      module::set_var(
        "ecard", "sender", $form->ecard->sender->value);
      module::set_var(
        "ecard", "subject", $form->ecard->subject->value);
      module::set_var(
        "ecard", "message", $form->ecard->message->value);
	  module::set_var(
		"ecard", "access_permissions",
                    $form->ecard->access_permissions->value);
    message::success(t("eCard settings updated"));
    url::redirect("admin/ecards");
  }

  private function _get_admin_form() {
    $form = new Forge("admin/ecards/save", "", "post",
                      array("id" => "g-ecards-admin-form"));
    $ecard_settings = $form->group("ecard")->label(t("eCard settings"));
	$ecard_settings->input("sender")->label(t('E-mail Sender (leave blank for a user-defined address)'))
		->value(module::get_var("ecard", "sender", ""));
	$ecard_settings->input("subject")->label(t('E-mail Subject'))
		->value(module::get_var("ecard", "subject", "You have been sent an eCard"));
	$ecard_settings->textarea("message")->label(t('E-mail Message'))
		->value(module::get_var("ecard", "message", "Hello %toname%, \r\n%fromname% has sent you an eCard. Click the image to be taken to the gallery."));
     $ecard_settings->dropdown("access_permissions")
      ->label(t("Who can send eCards?"))
      ->options(array("everybody" => t("Everybody"),
                      "registered_users" => t("Only registered users")))
      ->selected(module::get_var("ecard", "access_permissions"));
    $ecard_settings->submit("save")->value(t("Save"));
    return $form;
  }
}


<?php
namespace EA4V\Dynamic_Data\Data_Groups;
use \Voxel\Dynamic_Data\Tag as Tag;


if (!defined('ABSPATH')) {
	exit;
}


class Edit_Permisson_Data_Group extends \Voxel\Dynamic_Data\Data_Groups\Base_Data_Group
{
	public function __construct()
	{
		$this->post = \Voxel\get_current_post();

	}

	public function get_type(): string
	{
		return 'edit_permission';
	}

	public $post;





	protected function properties(): array
	{

		$raw_emails = (string) get_post_meta($this->post->get_id(), 'ea4v-share-edit-permission', true);
		$emails = array_values(array_unique(array_filter(array_map('trim', explode(',', $raw_emails)), 'is_email')));

		$user_ids = [];
		foreach ($emails as $email) {
			$wp_user = get_user_by('email', $email);
			if ($wp_user) {
				$user_ids[] = $wp_user->ID;
			}
		}
		$user_ids = array_values($user_ids);
		$users = array_map(function ($user_id) {
			return \Voxel\User::get($user_id);
		}, $user_ids);


		$properties = [

			'users' => Tag::Object_List('Users')->items(
				function () use ($users) {
				

					return $users;
				}
			)->properties(function ($index, $item) use ($users) {

				var_dump($index);


				return [
					'email' => Tag::String('Email')->render(function () use ($item) {
					return $item->get_email();
				}),
					'id' => Tag::Number('User ID')->render(function () use ($item) {
					return $item->get_id();
				}),
					'username' => Tag::String('Username')->render(function () use ($item) {
					return $item->get_username();
				}),
					'display_name' => Tag::String('Display name')->render(function () use ($item) {
					return $item->get_display_name();
				}),
					'avatar' => Tag::Number('Avatar')->render(function () use ($item) {
					return $item->get_avatar_id();
				}),
					'profile_url' => Tag::URL('Profile URL')->render(function () use ($item) {
					return get_author_posts_url($item->get_id());
				}),
					'profile_id' => Tag::Number('Profile ID')->render(function () use ($item) {
					return $item->get_profile_id();
				}),
					'first_name' => Tag::String('First name')->render(function () use ($item) {
					return $item->get_first_name();
				}),
					'last_name' => Tag::String('Last name')->render(function () use ($item) {
					return $item->get_last_name();
				}),

				];
			})
		];


		return $properties;
	}



}

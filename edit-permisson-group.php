<?php
namespace EA4V\Dynamic_Data\Data_Groups;
use \Voxel\Dynamic_Data\Tag as Tag;


if (!defined('ABSPATH')) {
	exit;
}


class Edit_Permisson_Group extends \Voxel\Dynamic_Data\Data_Groups\Base_Data_Group
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




		$properties = [
			'users' => Tag::Object_List('Users')->items(
				function () {
					$raw_emails = (string) get_post_meta($this->post->get_id(), 'ea4v-share-edit-permission', true);
					$emails = array_values(array_unique(array_filter(array_map('trim', explode(',', $raw_emails)), 'is_email')));
					return $emails;
				}
			)->properties(function ($index, $item) {
				var_dump($index);
				$user_id = get_user_by('email', $item);

				if ($user_id) {
					$user_id = $user_id->ID;
				} else {
					$user_id = null;
				}
				$user = $user_id ? \Voxel\User::get($user_id) : null;

				return [
					'email' => Tag::String('Email')->render(function () use ($item) {
						return $item;
					}),
					'id' => Tag::Number('User ID')->render(function () use ($user) {
						return $user ? $user->get_id() : null;
					}),
					'username' => Tag::String('Username')->render(function () use ($user) {
						return $user ? $user->get_username() : null;
					}),
					'display_name' => Tag::String('Display name')->render(function () use ($user) {
						return $user ? $user->get_display_name() : null;
					}),
					'avatar' => Tag::Number('Avatar')->render(function () use ($user) {
						var_dump($user ? $user->get_avatar_id() : null);
						return $user ? $user->get_avatar_id() : null;
					}),
					'profile_url' => Tag::URL('Profile URL')->render(function () use ($user) {
						return get_author_posts_url($user->get_id());
					}),
					'profile_id' => Tag::Number('Profile ID')->render(function () use ($user) {
						return $user ? $user->get_profile_id() : null;
					}),
					'first_name' => Tag::String('First name')->render(function () use ($user) {
						return $user ? $user->get_first_name() : null;
					}),
					'last_name' => Tag::String('Last name')->render(function () use ($user) {
						return $user ? $user->get_last_name() : null;
					}),

				];
			})

		];

		return $properties;
	}


}

<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');

    $this->player = User::factory()->player()->create();
});

it('allows a player to upload a profile image', function () {
    $image = UploadedFile::fake()->image('avatar.jpg');

    $response = $this->actingAs($this->player)
        ->from(route('player.profile'))
        ->post(route('player.profile.image.store'), [
            'profile_image' => $image,
        ]);

    $response->assertRedirect(route('player.profile'));

    $this->player->refresh();

    expect($this->player->profile_image)->not->toBeNull();

    Storage::disk('public')->assertExists(
        $this->player->profile_image
    );

    $response->assertSessionHas(
        'success',
        'Profile image uploaded successfully.'
    );
});

it('rejects uploading a non-image file', function () {
    $file = UploadedFile::fake()->create(
        'document.pdf',
        100,
        'application/pdf'
    );

    $response = $this->actingAs($this->player)
        ->from(route('player.profile'))
        ->post(route('player.profile.image.store'), [
            'profile_image' => $file,
        ]);

    $response->assertSessionHasErrors('profile_image');

    expect($this->player->fresh()->profile_image)->toBeNull();
});

it('rejects uploading an image larger than 2 MB', function () {
    $image = UploadedFile::fake()
        ->image('large.jpg')
        ->size(2049);

    $response = $this->actingAs($this->player)
        ->from(route('player.profile'))
        ->post(route('player.profile.image.store'), [
            'profile_image' => $image,
        ]);

    $response->assertSessionHasErrors('profile_image');

    expect($this->player->fresh()->profile_image)->toBeNull();
});

it('rejects uploading an unsupported image format', function () {
    $image = UploadedFile::fake()->create(
        'avatar.gif',
        100,
        'image/gif'
    );

    $response = $this->actingAs($this->player)
        ->from(route('player.profile'))
        ->post(route('player.profile.image.store'), [
            'profile_image' => $image,
        ]);

    $response->assertSessionHasErrors('profile_image');
});

it('deletes the old image when replacing a profile image', function () {
    $oldPath = 'profile-images/old-avatar.jpg';

    Storage::disk('public')->put($oldPath, 'old image content');

    $this->player->update([
        'profile_image' => $oldPath,
    ]);

    $newImage = UploadedFile::fake()->image('new-avatar.jpg');

    $response = $this->actingAs($this->player)
        ->from(route('player.profile'))
        ->post(route('player.profile.image.store'), [
            'profile_image' => $newImage,
        ]);

    $response->assertRedirect(route('player.profile'));

    $this->player->refresh();

    expect($this->player->profile_image)
        ->not->toBe($oldPath);

    Storage::disk('public')->assertMissing($oldPath);

    Storage::disk('public')->assertExists(
        $this->player->profile_image
    );
});

it('allows a player to remove their profile image', function () {
    $path = 'profile-images/avatar.jpg';

    Storage::disk('public')->put($path, 'image content');

    $this->player->update([
        'profile_image' => $path,
    ]);

    $response = $this->actingAs($this->player)
        ->from(route('player.profile'))
        ->delete(route('player.profile.image.destroy'));

    $response->assertRedirect(route('player.profile'));

    expect($this->player->fresh()->profile_image)->toBeNull();

    Storage::disk('public')->assertMissing($path);

    $response->assertSessionHas(
        'success',
        'Profile image removed successfully.'
    );
});

it('allows removing a profile image when no image exists', function () {
    $response = $this->actingAs($this->player)
        ->from(route('player.profile'))
        ->delete(route('player.profile.image.destroy'));

    $response->assertRedirect(route('player.profile'));

    expect($this->player->fresh()->profile_image)->toBeNull();

    $response->assertSessionHas(
        'success',
        'Profile image removed successfully.'
    );
});

it('prevents guests from uploading profile images', function () {
    $image = UploadedFile::fake()->image('avatar.jpg');

    $response = $this->post(
        route('player.profile.image.store'),
        ['profile_image' => $image]
    );

    $response->assertRedirect(route('login'));
});

it('prevents guests from deleting profile images', function () {
    $response = $this->delete(
        route('player.profile.image.destroy')
    );

    $response->assertRedirect(route('login'));
});
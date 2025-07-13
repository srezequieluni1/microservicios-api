<?php

it('api ping returns successful response', function () {
    $response = $this->get('/api/ping');

    $response->assertStatus(200);
    $response->assertJson([
        'status' => 'ok'
    ]);
});

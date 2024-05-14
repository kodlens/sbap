<template>
    <b-navbar>
        <template #brand>
            <b-navbar-item>
                <h1 class="title is-4">QR FRANCHISE</h1>
            </b-navbar-item>
        </template>
        <template #start>
            <b-navbar-item href="#">
                Home
            </b-navbar-item>
            <b-navbar-item href="#">
                Documentation
            </b-navbar-item>
            <b-navbar-dropdown label="Info">
                <b-navbar-item href="#">
                    About
                </b-navbar-item>
                <b-navbar-item href="#">
                    Contact
                </b-navbar-item>
            </b-navbar-dropdown>
        </template>

        <template #end>
            <b-navbar-item tag="div">
                <div class="buttons">
                    <a class="button is-primary">
                        <strong>Login</strong>
                    </a>
                   
                </div>
            </b-navbar-item>
        </template>
    </b-navbar>
</template>
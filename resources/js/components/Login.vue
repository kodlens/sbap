<template>

    <div class="page-container">

        <form @submit.prevent="submit">

            <div class="box login-card">

                <div class="">
                    <div class="box-heading-text">
                        SMART FINANCE MANAGER
                    </div>
                    <div class="img-container">
                        <img class="img" src="/img/logo_small.png" />
                    </div>
                </div>

                <div class="">
                    <div class="cred-text">
                        SECURITY LOGIN
                    </div>
                    <b-field label="Username" label-position="on-border"
                             :type="this.errors.username ? 'is-danger':''"
                             :message="this.errors.username ? this.errors.username[0] : ''">
                        <b-input type="text" v-model="fields.username" placeholder="Username" />
                    </b-field>

                    <b-field label="Password" label-position="on-border">
                        <b-input type="password" v-model="fields.password" password-reveal placeholder="Password" />
                    </b-field>

                    <div class="buttons is-center pb-4">
                        <button :class="btnClass">LOGIN</button>
                    </div>
                </div>

            </div>

        </form>

    </div>

</template>

<script>

export default {

    data(){
        return {
            fields: {
                username: '',
                password: '',
            },

            btnClass: {
                'is-primary': true,
                'is-loading': false,
                'button': true,
                'is-fullwidth' : true
            },

            errors: {},

        }
    },

    methods: {
        submit: function(){
            this.btnClass['is-loading'] = true;

            axios.post('/custom-login', this.fields).then(res=>{
                this.btnClass['is-loading'] = false;

                window.location = '/'

            }).catch(err=>{
            this.btnClass['is-loading'] = false;
                this.errors = err.response.data.errors;

            })
        }
    }
}
</script>


<style scoped>

    .page-container{
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .login-card{
        max-width: 800px;
        display: flex;
    }

    .login-card > div {
        margin: 6px;
        width: 100%;
    }

    .cred-text{
        font-weight: bold;
        margin-bottom: 20px;
    }

    .img-container{
        display: flex;
        justify-content: center;
    }

    .img{
        height: 150px;
    }
    .box-heading-text{
        font-weight: bold;
        font-size: 1.4em;
        text-align: center;
        padding: 15px;
    }


    @media screen and (max-width: 600px) {
        .login-card {
            flex-direction: column;
        }
    }



</style>

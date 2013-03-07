<section class="title">
        <!-- We'll use $this->method to switch between sample.create & sample.edit -->
        <h4><?php echo lang('instagram:'.$this->method); ?></h4>
</section>

<section class="item">

        <div class="content">

                <?php
                if($this->session->userdata('instagram-token'))
                {
                        echo 'You have authorized with Instagram: ' . $this->session->userdata('instagram-username');
                }
                ?>

                <p>Instagram’s API uses the OAuth 2.0 protocol for simple, but effective authentication and authorization. OAuth 2.0 is much easier to use than previous schemes; developers can start using the Instagram API almost immediately. The one thing to keep in mind is that all requests to the API must be made over SSL.</p>
                <?php echo anchor($this->instagram_api->instagramLogin(), 'Login w/ Instagram', 'class="btn blue"'); ?>

        </div>

</section>

<?php include 'inc/header.php'; ?>

<!-- Breadcrumb -->
    <section id="subheader" class="bc01 no-bottom" data-stellar-background-ratio="0.5">
        <div class="overlay">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                            <h1>About Us
                                <span>Clean Machine</span>
                            </h1>
                        <div class="small-border wow flipInY" data-wow-delay=".8s" data-wow-duration=".8s"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="clearfix"></div>

<!-- Content -->
    <div id="content">
        <div class="container">
			<?php echo _html($_DATA['aboutus']['content']) ; ?>

        </div>
    </div>

<?php include 'inc/footer.php'; ?>
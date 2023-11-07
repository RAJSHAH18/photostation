<!DOCTYPE html>
<html lang="en">

<?php include_once("head.php"); ?>

<body>
    <?php include_once("header.php"); ?>
    <main>
        <div class="container my-5">
            <h1 class="text-center">Photography FAQ</h1>
            <div class="accordion" id="faqAccordion">
                <div class="card">
                    <div class="card-header" id="faq1">
                        <h6 class="mb-0">

                            What type of photography services do you offer?
                        </h6>
                    </div>
                    <div id="collapse1" class="collapse show" aria-labelledby="faq1" data-parent="#faqAccordion">
                        <div class="card-body">
                            We offer a wide range of photography services, including portrait
                            photography, event photography, product photography, and
                            architectural photography. Our experienced photographers can help
                            you capture the moments that matter most.
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="faq1">
                        <h6 class="mb-0">

                            How much do your photography services cost?
                        </h6>
                    </div>
                    <div id="collapse1" class="collapse show" aria-labelledby="faq1" data-parent="#faqAccordion">
                        <div class="card-body">
                            The cost of our photography services varies depending on the type of session and the number
                            of photos you would like to receive. Please contact us for a customized quote based on your
                            specific needs.
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="faq1">
                        <h6 class="mb-0">

                            Do you offer outdoor photography sessions?
                        </h6>
                    </div>
                    <div id="collapse1" class="collapse show" aria-labelledby="faq1" data-parent="#faqAccordion">
                        <div class="card-body">
                            Yes, we offer outdoor photography sessions in a variety of locations, including parks,
                            beaches, and other scenic areas.
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </main>
    <?php include_once("footer.php") ?>
    <script src="site.js"></script>

</body>

</html>
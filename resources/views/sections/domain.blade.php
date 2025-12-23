<div class="col-12 py-5 bg-white">
    <div class="container px-3">
        <div class="text-center">
            <h3>Find Your Perfect Domain Name</h3>
            <p class="mb-5">Search for available domains and secure your online presence today</p>
        </div>
        <div class="row gy-4 justify-content-center">
            <div class="col-xl-8 col-lg-8">
                
                <!-- Domain Search Form -->
                <form action="{{ route('search.domain') }}" method="GET" class="domain-search-form">
                    <div class="input-group">
                        <input type="text" name="domain" class="form-control form-control-lg" 
                               placeholder="Enter domain name (e.g., example.com)" required>
                        <button class="btn btn-primary btn-lg" type="submit">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                    <div class="form-text mt-2">
                        Try: yourbusiness.com, yourname.net, idea.org
                    </div>
                </form>

            </div>
            <div class="col-md-6 text-center">
                <p class="mt-3">
                    Secure your domain name with competitive pricing and excellent support. 
                    All domains include free privacy protection and easy management tools.
                </p>
            </div>
        </div>
    </div>
</div>
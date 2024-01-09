@include('includes.header-before-login')

            <main class="content px-3 py-2">
                <div class="container-fluid">

                    <!-- Contact page  start -->
                    <div class="contact-page">
                        <div class="all-history-page">
                            <!-- ***** Contact page start ***** -->
                        
                            {{-- <div class="container"> --}}
                                <div class="row">
                                  <div class="col-lg-12 my-4">
                                    <div class="heading-section">
                                      <h4 class="text-center"><em>Contact </em></h4>
                                    </div>
                                    <div class="container">
                                      <div class="contact__wrapper shadow-lg mt-n9">
                                          <div class="row no-gutters">
                                      
                                              <div class="col contact-form__wrapper p-5 order-lg-1">
                                                  <form class="contact-form form-validate" action="{{ route('contact.submit') }}" method="POST">
                                                    @csrf
                                                      <div class="row">
                                                          <div class="col-sm-6 mb-3">
                                                              <div class="form-group">
                                                                  <label class="required-field" for="name">Name</label>
                                                                  <input type="text" class="form-control" id="name" name="name" placeholder="Your Full Name">
                                                              </div>
                                                          </div>
                                      
                                                          <div class="col-sm-6 mb-3">
                                                              <div class="form-group">
                                                                  <label for="email">Email:</label>
                                                                  <input type="email" class="form-control" id="email" name="email" placeholder="Youremail@example.com" required>
                                                              </div>
                                                          </div>
                                      
                                                          <div class="col-sm-6 mb-3">
                                                              <div class="form-group">
                                                                  <label class="required-field" for="subject">Subject</label>
                                                                  <input type="text" class="form-control" id="subject" name="subject" placeholder="Purpose of Contact title" maxlength="255" required>
                                                              </div>
                                                          </div>
                                      
                                                          <div class="col-sm-6 mb-3">
                                                              <div class="form-group">
                                                                  <label for="phone">Department</label>
                                                                  <select class="form-select form-select-md mb-3" name="department" required>
                                                                    <option value=''>Open this select menu</option>
                                                                    @foreach ($departments as $department)
                                                                        <option value="{{ $department->name }}">{{ $department->name }}</option>
                                                                    @endforeach
                                                                  </select>
                                                              </div>
                                                          </div>
                                      
                                                          <div class="col-sm-12 mb-3">
                                                              <div class="form-group">
                                                                  <label class="required-field" for="message">How can we help?</label>
                                                                  <textarea class="form-control" id="message" name="message" rows="12" maxlength="1000" minlength="20" placeholder="Hi there, I would like to....." required></textarea>
                                                              </div>
                                                          </div>
                                      
                                                          <div class="col-sm-12 mb-3">
                                                              <button type="submit" class="btn btn-primary">Submit</button>
                                                          </div>
                                      
                                                      </div>
                                                  </form>
                                              </div>
                                              <!-- End Contact Form Wrapper -->
                                      
                                          </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            {{-- </div> --}}
                        
                            <!-- Contact page end -->
                        </div>
                    </div>
                </div>

@include('includes.footer-before-login')
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
            <div class="card-body d-flex justify-content-center">
                <h4>
                    This is a home page, and here I explain how to use my website
                </h4>
            </div>
            <div class="d-flex m-3">
                
                <div class="accordion w-full" id="accordionExample">
                    <div class="accordion-item">
                      <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                          Do Login/Register actions require a real email?
                        </button>
                      </h2>
                      <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                          <strong>
                              No. You can register or login even as a jane@doe.com or example@example.net, the app won't check the validity of the email. However, it should be formed as an email, with an @ sign and  "." + ending (like .com, .net etc.).
                            </strong> 
                        </div>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                          How to preview public global files, staying unregistered?
                        </button>
                      </h2>
                      <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                          <strong>Just click on "Public Global Files" link, find a file that you want to preview and preview it by clicking a button "Preview" on the file's card. I'll seed the website with some global files for you to check how that works.</strong> 
                        </div>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                          After registration, what can I do to check how your website works?
                        </button>
                      </h2>
                      <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                          <strong>You can post Global Files, which can be public or contacts-only (visible only to your contacts). </strong> This can be achieved by going to GlobalFiles->Public->Actions->Post A Public File or GlobalFiles->Contacts-only->Actions->Post A Contacts-only File.
                          <strong>You can save files for a personal use.</strong> For this, you need to go to Files->Personal->Actions->Save A File For A Personal Use.
                          <strong>You can unregister, then create a second account and send a contact request to your first account using first account's public id.</strong> For this you need to go to Contacts->Contact Requests->Send A Contact Request and paste the public id into the input field, then submit the form. To review your public id you have to go to {Your_User_Name}->Profile. 
                          After a succsessfull submition of the request you can then login to your first account again and accept the request at Contacts->Contact Requests. After a successfull acceptation of the request your two accounts will become contacts.
                          <strong>And then you can send files to your contacts.</strong> For this, go to Contacts->Dasboard, choose a contact to which you want to send files, and click Actions->Send A File on the contact's card.
                        </div>
                      </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            How the form for sending files works?
                          </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                          <div class="accordion-body">
                            <strong>Title.</strong> It is a title of your file, this is how you call it.
                            <strong>Description.</strong> It is a description of the file.
                            <strong>Category.</strong> Here you tell other users to which category the file belongs.
                            <strong>File Upload Type.</strong> Here you have two options: "for small and medium size files" and "for big files (One file at a time upload)". "for small and medium size files" means you can upload files up to 40 - 50 mb in size each. "for big files (One file at a time upload)" means that you can upload only one file at a time, but a file large enough, up to 10GB in size.
                            <strong>Files input field OR [browse...] button (depends on the file upload type)</strong>. Here you choose files which you send/post. If its an input field, then you can choose multiple files at once. If it's a browse button (sorry for the lack of styling, forgot about that), you can choose only ONE file (well, you can choose multiple files, but it just won't work, you will need to reload the page).  
                            <strong>Archivation format field (appears if you select multiple files in "small and medium size files" upload mode).</strong> Here you can choose the archivation type for your files, or you can say that you want none (works only when you send files to your contacts or save files for yourself; "none" option is absent if you want to post a global file), in case of choosing which the files will be sent/saved separately.
                            <strong>And, finally, the File accessibility select (doesn't appear when you send files to someone).</strong>This select is created in case you wanted to post a public file, but then had second thoughts, and decided to make it private or otherwise. Private stands for personal files, Contacts-only - for contacts-only file, and Public -for public.  
                        </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <h2 class="accordion-header">
                          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            Additional information (Profile page, Messages, Comments and more).
                          </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                          <div class="accordion-body">
                            <strong>Profile page features user's name, email, profile image and public id.</strong> You can do only two things with your profile: <strong>edit it or reset the public id.</strong>
                            <strong> Messages are system messages that inform you about something.</strong> You can delete one message by clicking a delete button on it's card, delete multiple messages or delet all messages (these functions are available in "Messages->Dashboard->Actions").
                            <strong> You can post comments to a contacts-only or to a public file if you are registered.</strong>
                            <strong> Likes.</strong> If you are registered, you can like Global Files (contacts-only or public).
                            <strong>It's important to mention (again) that this project is a back-end skills demo, not a fullstack fully functioning project. So if you are unipressed by the styling of the website, remember that.</strong>
                        </div>
                        </div>
                      </div>
                  </div>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection

document.addEventListener("DOMContentLoaded", function(){

    const buttons = document.querySelectorAll(".tab-btn");
    const tabs = document.querySelectorAll(".tab-content");
    const currentTab = document.getElementById("currentTab");
    const pageTitle = document.getElementById("pageTitle");
    const pageSub = document.getElementById("pageSub");

    const titles = {
        summary:["Summary","Summary of employee's record."],
        basic:["Basic Info","Basic employee information."],
        work:["Work Profile","Employee work profile and reporting managers."],
        policies:["Policies","Employee policies details."],
        salary:["Salary","Employee salary details."],
        identity:["Identity","Employee identity details."],
        bgcheck:["BG Check","Background verification details."],
        address:["Addresses","Employee address details."],
        documents:["Documents","Employee documents details."],
        assets:["Assets","Assets assigned to employee."],
        family:["Family Members","Family member details."],
        permissions:["Permissions","Employee permission details."],
        login:["Login & Access","Login and access details."]
    };

    buttons.forEach(btn => {
        btn.addEventListener("click", function(){

            const id = this.dataset.tab;
            const targetTab = document.getElementById(id);

            buttons.forEach(b => b.classList.remove("active"));
            tabs.forEach(t => t.classList.remove("active"));

            this.classList.add("active");

            if(targetTab){
                targetTab.classList.add("active");
            }

            if(currentTab){
                currentTab.innerText = titles[id][0];
            }

            if(pageTitle){
                pageTitle.innerText = titles[id][0];
            }

            if(pageSub){
                pageSub.innerText = titles[id][1];
            }
        });
    });

    const workModal = document.getElementById("workModal");
    const openBtn = document.getElementById("openWorkModal");
    const closeBtn = document.getElementById("closeWorkModal");
    const cancelBtn = document.getElementById("cancelWorkModal");

    if(openBtn && workModal){
        openBtn.addEventListener("click", function(){
            workModal.classList.add("show");
        });
    }

    if(closeBtn && workModal){
        closeBtn.addEventListener("click", function(){
            workModal.classList.remove("show");
        });
    }

    if(cancelBtn && workModal){
        cancelBtn.addEventListener("click", function(){
            workModal.classList.remove("show");
        });
    }

    if(workModal){
        workModal.addEventListener("click", function(e){
            if(e.target === workModal){
                workModal.classList.remove("show");
            }
        });
    }

    const workRevisionModal = document.getElementById('workRevisionModal');

    if(workRevisionModal){
        workRevisionModal.addEventListener('click', function(e){
            if(e.target === workRevisionModal){
                closeWorkRevisionModal();
            }
        });
    }

    const managerModal = document.getElementById('managerModal');

    if(managerModal){
        managerModal.addEventListener('click', function(e){
            if(e.target === managerModal){
                closeManagerModal();
            }
        });
    }

});

function openJoiningModal(){
    const modal = document.getElementById('joiningModal');

    if(modal){
        modal.classList.add('active');
    }
}

function closeJoiningModal(){
    const modal = document.getElementById('joiningModal');

    if(modal){
        modal.classList.remove('active');
    }
}

function openWorkRevisionModal(){
    const modal = document.getElementById('workRevisionModal');

    if(modal){
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeWorkRevisionModal(){
    const modal = document.getElementById('workRevisionModal');

    if(modal){
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }
}

function openManagerModal(title){
    const modalTitle = document.getElementById('managerModalTitle');
    const modal = document.getElementById('managerModal');

    if(modalTitle){
        modalTitle.innerText = title;
    }

    if(modal){
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeManagerModal(){
    const modal = document.getElementById('managerModal');

    if(modal){
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }
}

document.addEventListener('keydown', function(e){
    if(e.key === 'Escape'){
        closeJoiningModal();
        closeWorkRevisionModal();
        closeManagerModal();
    }
});




    document.addEventListener("DOMContentLoaded", function(){



    const buttons = document.querySelectorAll(".tab-btn");

    const tabs = document.querySelectorAll(".tab-content");

    const currentTab = document.getElementById("currentTab");

    const pageTitle = document.getElementById("pageTitle");

    const pageSub = document.getElementById("pageSub");



    const titles = {

        summary:["Summary","Summary of employee's record."],

        basic:["Basic Info","Basic employee information."],

        work:["Work Profile","Employee work profile and reporting managers."],

        policies:["Policies","Employee policies details."],

        salary:["Salary","Employee salary details."],

        identity:["Identity","Employee identity details."],

        bgcheck:["BG Check","Background verification details."],

        address:["Addresses","Employee address details."],

        documents:["Documents","Employee documents details."],

        assets:["Assets","Assets assigned to employee."],

        family:["Family Members","Family member details."],

        permissions:["Permissions","Employee permission details."],

        login:["Login & Access","Login and access details."]

    };



    buttons.forEach(btn => {

        btn.addEventListener("click", function(){



            const id = this.dataset.tab;

            const targetTab = document.getElementById(id);



            buttons.forEach(b => b.classList.remove("active"));

            tabs.forEach(t => t.classList.remove("active"));



            this.classList.add("active");



            if(targetTab){

                targetTab.classList.add("active");

            }



            if(currentTab){

                currentTab.innerText = titles[id][0];

            }



            if(pageTitle){

                pageTitle.innerText = titles[id][0];

            }



            if(pageSub){

                pageSub.innerText = titles[id][1];

            }

        });

    });



    const workModal = document.getElementById("workModal");

    const openBtn = document.getElementById("openWorkModal");

    const closeBtn = document.getElementById("closeWorkModal");

    const cancelBtn = document.getElementById("cancelWorkModal");



    if(openBtn && workModal){

        openBtn.addEventListener("click", function(){

            workModal.classList.add("show");

        });

    }



    if(closeBtn && workModal){

        closeBtn.addEventListener("click", function(){

            workModal.classList.remove("show");

        });

    }



    if(cancelBtn && workModal){

        cancelBtn.addEventListener("click", function(){

            workModal.classList.remove("show");

        });

    }



    if(workModal){

        workModal.addEventListener("click", function(e){

            if(e.target === workModal){

                workModal.classList.remove("show");

            }

        });

    }



    const workRevisionModal = document.getElementById('workRevisionModal');



    if(workRevisionModal){

        workRevisionModal.addEventListener('click', function(e){

            if(e.target === workRevisionModal){

                closeWorkRevisionModal();

            }

        });

    }



    const managerModal = document.getElementById('managerModal');



    if(managerModal){

        managerModal.addEventListener('click', function(e){

            if(e.target === managerModal){

                closeManagerModal();

            }

        });

    }



});



function openJoiningModal(){

    const modal = document.getElementById('joiningModal');



    if(modal){

        modal.classList.add('active');

    }

}



function closeJoiningModal(){

    const modal = document.getElementById('joiningModal');



    if(modal){

        modal.classList.remove('active');

    }

}



function openWorkRevisionModal(){

    const modal = document.getElementById('workRevisionModal');



    if(modal){

        modal.classList.add('show');

        document.body.style.overflow = 'hidden';

    }

}



function closeWorkRevisionModal(){

    const modal = document.getElementById('workRevisionModal');



    if(modal){

        modal.classList.remove('show');

        document.body.style.overflow = '';

    }

}



function openManagerModal(title){

    const modalTitle = document.getElementById('managerModalTitle');

    const modal = document.getElementById('managerModal');



    if(modalTitle){

        modalTitle.innerText = title;

    }



    if(modal){

        modal.classList.add('show');

        document.body.style.overflow = 'hidden';

    }

}



function closeManagerModal(){

    const modal = document.getElementById('managerModal');



    if(modal){

        modal.classList.remove('show');

        document.body.style.overflow = '';

    }

}



document.addEventListener('keydown', function(e){

    if(e.key === 'Escape'){

        closeJoiningModal();

        closeWorkRevisionModal();

        closeManagerModal();

    }

});





function openSalaryModal(){
    const modal = document.getElementById('salaryModal');
    if(modal){
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeSalaryModal(){
    const modal = document.getElementById('salaryModal');
    if(modal){
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }
}

document.addEventListener('DOMContentLoaded', function(){
    const modal = document.getElementById('salaryModal');

    if(modal){
        modal.addEventListener('click', function(e){
            if(e.target === modal){
                closeSalaryModal();
            }
        });
    }

    document.addEventListener('keydown', function(e){
        if(e.key === 'Escape'){
            closeSalaryModal();
        }
    });
});




function openAddressModal(){
    const modal = document.getElementById('addressModal');

    if(modal){
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeAddressModal(){
    const modal = document.getElementById('addressModal');

    if(modal){
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }
}

document.addEventListener('click', function(e){
    const modal = document.getElementById('addressModal');

    if(modal && e.target === modal){
        closeAddressModal();
    }
});

document.addEventListener('keydown', function(e){
    if(e.key === 'Escape'){
        closeAddressModal();
    }
});



// 



function openDocumentModal(){
    const modal = document.getElementById('documentModal');

    if(modal){
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeDocumentModal(){
    const modal = document.getElementById('documentModal');

    if(modal){
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }
}

document.addEventListener('click', function(e){
    const modal = document.getElementById('documentModal');

    if(modal && e.target === modal){
        closeDocumentModal();
    }
});

document.addEventListener('keydown', function(e){
    if(e.key === 'Escape'){
        closeDocumentModal();
    }
});





function openAssetModal(){
    const modal = document.getElementById('assetModal');

    if(modal){
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeAssetModal(){
    const modal = document.getElementById('assetModal');

    if(modal){
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }
}

document.addEventListener('click', function(e){
    const modal = document.getElementById('assetModal');

    if(modal && e.target === modal){
        closeAssetModal();
    }
});

document.addEventListener('keydown', function(e){
    if(e.key === 'Escape'){
        closeAssetModal();
    }
});





function openFamilyModal(){
    const modal = document.getElementById('familyModal');

    if(modal){
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeFamilyModal(){
    const modal = document.getElementById('familyModal');

    if(modal){
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }
}

document.addEventListener('click', function(e){
    const modal = document.getElementById('familyModal');

    if(modal && e.target === modal){
        closeFamilyModal();
    }
});

document.addEventListener('keydown', function(e){
    if(e.key === 'Escape'){
        closeFamilyModal();
    }
});






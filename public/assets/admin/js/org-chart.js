function exportChart(){
    alert('Chart export function called');
}

function loadOrgChart(){
    var icon = document.querySelector('#orgLoadBtn i');

    if(icon){
        icon.classList.add('org-load-spin');
    }

    setTimeout(function(){
        if(icon){
            icon.classList.remove('org-load-spin');
        }
    }, 800);
}

function searchEmployee(){
    var input = document.getElementById('employeeSearch');
    var nodes = document.querySelectorAll('.org-node');

    if(!input){
        return;
    }

    var value = input.value.toLowerCase().trim();

    nodes.forEach(function(node){
        node.classList.remove('highlight');

        if(value === ''){
            return;
        }

        var text = node.innerText.toLowerCase();

        if(text.indexOf(value) !== -1){
            node.classList.add('highlight');
        }
    });
}

document.addEventListener('DOMContentLoaded', function(){

    var loadBtn = document.getElementById('orgLoadBtn');

    if(loadBtn){
        loadBtn.addEventListener('click', function(){
            loadOrgChart();
        });
    }

    var searchInput = document.getElementById('employeeSearch');

    if(searchInput){
        searchInput.addEventListener('keyup', function(){
            searchEmployee();
        });
    }

});
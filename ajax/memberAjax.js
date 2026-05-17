document.addEventListener("DOMContentLoaded", function () {

    var csrfToken = document.getElementById("csrf_token_value").value;

    document.querySelectorAll(".deleteBtn").forEach(function (btn) {

        btn.addEventListener("click", function (e) {

            e.preventDefault();

            var id   = this.getAttribute("data-id");

            var name = this.getAttribute("data-name");

            if (!confirm("Delete member \"" + name + "\"?\nThis cannot be undone.")) {
                return;
            }

            var xhttp = new XMLHttpRequest();

            xhttp.open("POST", "../controllers/deleteMember.php", true);

            xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhttp.send("id=" + id + "&csrf_token=" + encodeURIComponent(csrfToken));

            xhttp.onreadystatechange = function () {

                if (this.readyState === 4 && this.status === 200) {

                    var res;

                    try {
                        res = JSON.parse(this.responseText);

                    } catch (err) {
                        alert("Server error!");

                        return;
                    }
                    if (res.status === "success") {

                        var row = document.getElementById("row" + id);

                        if (row) row.remove();

                        alert("Member deleted successfully!");

                    } else {
                        
                        alert("Delete failed: " + res.message);
                    }
                }
            };
        });
    });
});
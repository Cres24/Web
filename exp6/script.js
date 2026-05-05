function showData() {
    let name = document.getElementById("name").value;
    let email = document.getElementById("email").value;

    // gender
    let gender = document.querySelector('input[name="gender"]:checked');
    if (gender) {
        gender = gender.value;
    } else {
        gender = "Not selected";
    }

    // skills
    let skills = document.querySelectorAll('input[name="skill"]:checked');
    let skillList = [];

    skills.forEach(function(skill){
        skillList.push(skill.value);
    });

    alert(
        "Name: " + name +
        "\nEmail: " + email +
        "\nGender: " + gender +
        "\nSkills: " + skillList.join(", ")
    );

    return false;
}
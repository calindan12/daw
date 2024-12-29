<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Despre Proiect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    
    <div class="container mt-5">
        <h1 class="text-center mb-4">Prezentare Aplicație</h1>
        <section>
            <h2>Introducere</h2>
            <p>Aplicația noastră web oferă o soluție centralizată pentru gestionarea studenților, profesorilor, cursurilor facultative și notelor obtinute la finalizarea acestora în cadrul unei universități.</p>
        </section>
        <section>
            <h2>Funcționalități</h2>
            <ul>
                <li>Înregistrare și autentificare utilizatori</li>
                <li>Gestionarea utilizatorilor: studenți, profesori</li>
                <li>Adăugare cursuri facultative si repartizare unui profesor</li>
                <li>Inscrierea studentilor la cursurile facultative pentru care opteaza</li>
                <li>Notarea studentilor de catre profesor pentru fiecare curs facultativ</li>
                <li>Elemente statistice ale site-ului (website analytics): vizitatori, accesari, timp petrecut pe pagina respectiva</li>
                <li>Reprezentare grafica</li>

            </ul>
        </section>
        <section>
            <h2>Beneficii</h2>
            <p>Aplicația simplifică administrarea utilizatorilor informațiilor educaționale și oferă transparență în relația profesor-student.</p>
        </section>
        <section>
            <h2>Tehnologii Utilizate</h2>
            <p>Am utilizat PHP pentru backend, PHPMailer pentru trimiterea mail-urilor, Chart pentru realizarea graficului, MySQL pentru baza de date și Bootstrap pentru design responsive.</p>
        </section>


        <h1 class="text-center mb-4">Descrierea Arhitecturii Aplicației</h1>


        <section>
            <h2>1. Roluri în Aplicație</h2>
            <ul>
                <li><strong>Admin:</strong> Gestionează utilizatorii (adaugă, editează, șterge), cursurile și rapoartele.</li>
                <li><strong>Profesor:</strong> Vizualizează cursurile de care este responsabil și noteaza studentii.</li>
                <li><strong>Student:</strong> Vizualizează cursurile disponibile, notele și se înscrie la cursuri.</li>
            </ul>
        </section>

        <section>
            <h2>2. Entități și Procese</h2>
            <h3>Entități principale</h3>
            <ul>
                <li><strong>Users:</strong> Informații despre utilizatori (ID, nume, rol, email, facultate).</li>
                <li><strong>Courses:</strong> Cursuri (ID, nume, număr de credite, profesor responsabil).</li>
                <li><strong>Enrollments:</strong> Înscrieri la cursuri (ID, utilizator, curs, notă, dată înscriere).</li>
            </ul>
            <h3>Procese principale</h3>
            <ul>
                <li>Înregistrarea unui utilizator (Admin/Profesor/Student).</li>
                <li>Crearea și vizualizarea cursurilor.</li>
                <li>Adăugarea și modificarea notelor.</li>
            </ul>
        </section>

        <section>
            <h2>3. Relațiile între Entități</h2>
            <p>Entitățile sunt conectate prin următoarele relații:</p>
            <ul>
                <li><strong>Users → Courses:</strong> Un profesor poate fi responsabil de mai multe cursuri (relație 1:M).</li>
                <li><strong>Users → Enrollments:</strong> Un student poate fi înscris la mai multe cursuri (relație 1:M).</li>
                <li><strong>Courses → Enrollments:</strong> Un curs poate avea mai mulți studenți înscriși (relație 1:M).</li>
            </ul>
        </section>

        <section>
            <h2>4. Componentele Principale</h2>
            <p>Aplicația este împărțită în următoarele componente:</p>
            <ul>
                <li><strong>Frontend:</strong> Bootstrap pentru design și interfață responsive.</li>
                <li><strong>Backend:</strong> PHP pentru gestionarea logicii aplicației și procesarea cererilor.</li>
                <li><strong>Bază de Date:</strong> MySQL pentru stocarea datelor, cu tabelele:
                    <ul>
                        <li><strong>users:</strong> Stochează informații despre utilizatori.</li>
                        <li><strong>courses:</strong> Stochează informații despre cursuri.</li>
                        <li><strong>enrollments:</strong> Conectează utilizatorii cu cursurile.</li>
                    </ul>
                </li>
            </ul>
        </section>

        <section>
    <h2>5. Descrierea Bazei de Date</h2>
    <p>Baza de date este structurată astfel:</p>
    <ul>
        <li><strong>users:</strong>
            <ul>
                <li><strong>id:</strong> Identificator unic.</li>
                <li><strong>username:</strong> Nume de utilizator.</li>
                <li><strong>email:</strong> Adresa de email.</li>
                <li><strong>idRole:</strong> Rolul utilizatorului (Admin, Profesor, Student).</li>
            </ul>
        </li>
        <li><strong>courses:</strong>
            <ul>
                <li><strong>id:</strong> Identificator unic al cursului.</li>
                <li><strong>name:</strong> Numele cursului.</li>
                <li><strong>credits:</strong> Numărul de credite alocate cursului.</li>
                <li><strong>id_professor:</strong> Referință la profesorul responsabil.</li>
            </ul>
        </li>
        <li><strong>enrollments:</strong>
            <ul>
                <li><strong>id:</strong> Identificator unic al înscrierii.</li>
                <li><strong>user_id:</strong> Referință la studentul înscris.</li>
                <li><strong>course_id:</strong> Referință la cursul înscris.</li>
                <li><strong>grade:</strong> Nota primită (opțional).</li>
                <li><strong>date:</strong> Data înscrierii sau notării.</li>
            </ul>
        </li>
    </ul>
    
    <div class="d-flex justify-content-center">
        <img src="./imagine/image.png" alt="Arhitectura bazei de date" class="img-fluid">
    </div>

    <h2>6. Descriere a solutiei de implementare propuse folosind diagramă UML din programul PlantUML.</h2>

    <div class="d-flex justify-content-center">
        <img src="./imagine/imageUML.png" alt="Arhitectura bazei de date" class="img-fluid">
    </div>
    <div class="text-center mt-5">
            <a href="./login/login.php" class="btn btn-primary btn-lg">Autentificare</a>
    </div>
</section>


    </div>
</body>
</html>

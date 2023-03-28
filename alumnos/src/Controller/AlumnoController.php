<?php

namespace App\Controller;

use App\Entity\Alumno;
use App\Form\AlumnoType;
use App\Repository\AlumnoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/*para la paginación*/
use Knp\Component\Pager\PaginatorInterface;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPaginationInterface;



/**
 * @Route("/")
 */
class AlumnoController extends AbstractController
{
    /**
     * @Route("/", name="app_alumno_index", methods={"GET"})
     */
    public function index(Request $request, AlumnoRepository $alumnoRepository, PaginatorInterface $paginator): Response
    {
        $alumnosQuery = $alumnoRepository->createQueryBuilder('a')->getQuery();

        $alumnos = $paginator->paginate(
        $alumnosQuery,
        $request->query->getInt('page', 1),
        4
    );

        return $this->render('alumno/index.html.twig', [
            'alumnos' =>$alumnos,
        ]);
    }

    /**
     * @Route("/new", name="app_alumno_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AlumnoRepository $alumnoRepository): Response
    {
        $alumno = new Alumno();
        $form = $this->createForm(AlumnoType::class, $alumno);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $alumnoRepository->add($alumno, true);

            return $this->redirectToRoute('app_alumno_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('alumno/new.html.twig', [
            'alumno' => $alumno,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_alumno_show", methods={"GET"})
     */
    public function show(Alumno $alumno): Response
    {
        return $this->render('alumno/show.html.twig', [
            'alumno' => $alumno,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_alumno_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Alumno $alumno, AlumnoRepository $alumnoRepository): Response
    {
        $form = $this->createForm(AlumnoType::class, $alumno);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $alumnoRepository->add($alumno, true);

            return $this->redirectToRoute('app_alumno_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('alumno/edit.html.twig', [
            'alumno' => $alumno,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_alumno_delete", methods={"POST"})
     */
    public function delete(Request $request, Alumno $alumno, AlumnoRepository $alumnoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$alumno->getId(), $request->request->get('_token'))) {
            $alumnoRepository->remove($alumno, true);
        }

        return $this->redirectToRoute('app_alumno_index', [], Response::HTTP_SEE_OTHER);
    }
}

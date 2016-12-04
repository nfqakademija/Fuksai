<?php
/**
 * Created by PhpStorm.
 * User: shalifar
 * Date: 16.12.2
 * Time: 21.45
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Subscriber;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class SubscribeController extends Controller
{
    /**
     * @Route("/subscribe", name="show_subscribe_form")
     *
     * @param Request
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function showForm(Request $request)
    {
        $subscriber = new Subscriber();

        $form = $this->createFormBuilder($subscriber)
            ->add('email', TextType::class)
            ->add('submit', SubmitType::class, ['label'=>'Subscribe'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $subscriber = $form->getData();

            if (!filter_var($subscriber->getEmail(), FILTER_VALIDATE_EMAIL)) {
                return $this->render('error/error.html.twig', [
                    'errorMsg' => 'Invalid e-mail!',
                ]);
            }

            //email check
            $users = $em->getRepository('AppBundle:Subscriber')->findBy(['email' => $subscriber->getEmail()]);
            if (isset($users[0])) {
                return $this->render('error/error.html.twig', [
                    'errorMsg' => 'Such e-mail already subscribed!',
                ]);
            }

            //keyName check
            do {
                $subscriber->setKeyName(rand(1000, 9999));
                $users = $em->getRepository('AppBundle:Subscriber')->findBy([
                    'keyName' => $subscriber->getKeyName()
                ]);
            } while (isset($users[0]));

            $em->persist($subscriber);
            $em->flush();
        }

        return $this->render('subscribe/subscribe_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

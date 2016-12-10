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
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
            ->add('email', EmailType::class)
            ->add('submit', SubmitType::class, ['label'=>'Subscribe'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $subscriber = $form->getData();

            //email check
            $user = $em->getRepository('AppBundle:Subscriber')->findOneBy(['email' => $subscriber->getEmail()]);
            if (!empty($user)) {
                return $this->render('error/error.html.twig', [
                    'errorMsg' => 'Such e-mail already subscribed!',
                ]);
            }

            $subscriber->setKeyName(md5(uniqid($subscriber->getEmail(), true)));

            $em->persist($subscriber);
            $em->flush();
        }

        return $this->render('subscribe/subscribe_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

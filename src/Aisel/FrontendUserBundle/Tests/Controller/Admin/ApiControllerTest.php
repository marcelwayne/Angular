<?php

/*
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aisel\FrontendUserBundle\Tests\Controller\Admin;

use Aisel\ResourceBundle\Tests\AbstractWebTestCase;

/**
 * ApiControllerTest
 *
 * @author Ivan Proskuryakov <volgodark@gmail.com>
 */
class ApiControllerTest extends AbstractWebTestCase
{

    public function setUp()
    {
        parent::setUp();

        $this->logInBackend();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testPostUserAction()
    {
        $users = $this
            ->em
            ->getRepository('Aisel\FrontendUserBundle\Entity\FrontendUser')
            ->findBy(['email' => 'test_frontend_user_aisel@aisel.co']);

        foreach ($users as $user) {
            $this->em->remove($user);
        }
        $this->em->flush();

        $data = [
            'email' => 'test_frontend_user_aisel@aisel.co',
            'plain_password' => 'test_frontend_user_aisel',
        ];

        $this->client->request(
            'POST',
            '/' . $this->api['backend'] . '/frontenduser/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $response = $this->client->getResponse();
        $content = $response->getContent();
        $statusCode = $response->getStatusCode();

        $this->client->request(
            'POST',
            '/' . $this->api['backend'] . '/frontenduser/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $response = $this->client->getResponse();
        $content = $response->getContent();
        $statusCode = $response->getStatusCode();
        $result = json_decode($content, true);

        $this->assertEquals($result['code'], 400);
        $this->assertEquals($result['errors']['email'], 'This value is already used.');
        $this->assertTrue(400 === $statusCode);
    }

    public function testGetUsersAction()
    {
        $this->client->request(
            'GET',
            '/' . $this->api['backend'] . '/frontenduser/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        $response = $this->client->getResponse();
        $content = $response->getContent();
        $statusCode = $response->getStatusCode();

        $this->assertTrue(200 === $statusCode);
        $this->assertJson($content);
    }

    public function testGetUserAction()
    {
        $user = $this
            ->em
            ->getRepository('Aisel\FrontendUserBundle\Entity\FrontendUser')
            ->findOneBy(['email' => 'test_frontend_user_aisel@aisel.co']);
        $id = $user->getId();


        $this->client->request(
            'GET',
            '/' . $this->api['backend'] . '/frontenduser/' . $id,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        $response = $this->client->getResponse();
        $content = $response->getContent();
        $statusCode = $response->getStatusCode();
        $result = json_decode($content, true);

        $this->assertTrue(200 === $statusCode);
        $this->assertEquals($result['id'], $user->getId());
    }

    public function testPutUserAction()
    {
        $passwordString = '000111222';
        $user = $this
            ->em
            ->getRepository('Aisel\FrontendUserBundle\Entity\FrontendUser')
            ->findOneBy(['email' => 'test_frontend_user_aisel@aisel.co']);
        $id = $user->getId();
        $oldPassword = $user->getPassword();

        $encoder = $this->getContainer()->get('security.encoder_factory')->getEncoder($user);
        $encodedPassword = $encoder->encodePassword(
            $passwordString,
            $user->getSalt()
        );

        $data = array(
            'id' => $id,
            'created_at' => '2015-05-26T05:04:54-0700',
            'updated_at' => '2015-05-26T05:04:54-0700',
            'last_login' => '2015-05-26T05:04:54-0700',
            'phone' => '+123',
            'enabled' => true,
            'locked' => false,
            'orders' => array(),
            'cart' => array(),
            'plain_password' => $passwordString,
        );

        $this->client->request(
            'PUT',
            '/' . $this->api['backend'] . '/frontenduser/' . $id,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $response = $this->client->getResponse();
        $content = $response->getContent();
        $statusCode = $response->getStatusCode();

        $this->em->clear();

        $user = $this
            ->em
            ->getRepository('Aisel\FrontendUserBundle\Entity\FrontendUser')
            ->findOneBy(['email' => 'test_frontend_user_aisel@aisel.co']);

        $this->assertNotEquals($oldPassword, $user->getPassword());
        $this->assertEquals($encodedPassword, $user->getPassword());
        $this->assertTrue(204 === $statusCode);
        $this->assertEmpty($content);
        $this->assertNotNull($user);
    }

    public function testDeleteUserAction()
    {
        $user = $this
            ->em
            ->getRepository('Aisel\FrontendUserBundle\Entity\FrontendUser')
            ->findOneBy(['email' => 'test_frontend_user_aisel@aisel.co']);
        $id = $user->getId();

        $this->client->request(
            'DELETE',
            '/' . $this->api['backend'] . '/frontenduser/' . $id,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        $response = $this->client->getResponse();
        $content = $response->getContent();
        $statusCode = $response->getStatusCode();

        $user = $this
            ->em
            ->getRepository('Aisel\FrontendUserBundle\Entity\FrontendUser')
            ->findOneBy(['email' => 'test_frontend_user_aisel@aisel.co']);

        $this->assertTrue(204 === $statusCode);
        $this->assertEmpty($content);
        $this->assertNull($user);
    }

}

import { Request, Response } from "express";
import { getCustomRepository, IsNull, Not } from "typeorm";
import { SurveysUsersRepository } from "../repositories/SurveysUsersRepository";

class NpsController {
    async execute(request: Request, response: Response) {
        const { survey_id } = request.params;

        const surveysUsersRepository = getCustomRepository(
            SurveysUsersRepository
        );
        const surveyUsers = await surveysUsersRepository.find({
            survey_id,
            value: Not(IsNull()),
        });

        const detractor = surveyUsers.filter(
            (survey) => survey.value >= 6 && survey.value <= 6
        ).length;

        const passives = surveyUsers.filter(
            (survey) => survey.value >= 7 && survey.value <= 8
        ).length;

        const promoters = surveyUsers.filter(
            (survey) => survey.value >= 9 && survey.value <= 10
        ).length;

        const totalAnswers = surveyUsers.length;
        const nps = Number(
            (((promoters - detractor) / totalAnswers) * 100).toFixed(2)
        );

        return response.json({
            detractor,
            passives,
            promoters,
            totalAnswers,
            nps: calculate,
        });
    }
}

export { NpsController };
